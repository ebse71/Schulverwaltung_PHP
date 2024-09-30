<?php
// Hata raporlamayı etkinleştir
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantısı
require '../pages/vt.php';
// Başlangıçta formu göstermek istiyoruz
$show_form = true;
// Rastgele şifre oluşturma fonksiyonu
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

$errors = []; // Hata mesajlarını saklamak için dizi
$message = ''; // Başarı veya genel hata mesajı

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al ve güvenli hale getir
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $additional_branch = trim($_POST['additional_branch'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $phone_number_1 = trim($_POST['phone_number_1'] ?? '');
    $phone_number_2 = trim($_POST['phone_number_2'] ?? '');
    $password = $_POST['password'] ?? generateRandomPassword();
	$role = 'teacher'; // Rolü sabit olarak 'teacher' olarak ayarlıyoruz.

    // VERİ DOĞRULAMA
    // İsim doğrulama
    if (empty($name)) {
        $errors['name'] = "İsim alanı boş bırakılamaz.";
    }

    // Soyisim doğrulama
    if (empty($surname)) {
        $errors['surname'] = "Soyisim alanı boş bırakılamaz.";
    }

    // E-posta adresinin geçerli olup olmadığını kontrol et
    if (empty($email)) {
        $errors['email'] = "E-posta alanı boş bırakılamaz.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Geçersiz e-posta adresi.";
    } else {
        // E-posta adresinin veritabanında olup olmadığını kontrol et
        $email_check_sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $dbh->prepare($email_check_sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $email_exists = $stmt->fetchColumn();

        if ($email_exists) {
            $errors['email'] = "Bu e-posta adresi zaten kayıtlı.";
        }
    }

    // Branş doğrulama
    if (empty($branch)) {
        $errors['branch'] = "Branş alanı boş bırakılamaz.";
    }

    // Doğum tarihi doğrulama
    if (empty($birth_date)) {
        $errors['birth_date'] = "Doğum tarihi alanı boş bırakılamaz.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birth_date)) {
        $errors['birth_date'] = "Geçersiz doğum tarihi formatı. (YYYY-AA-GG)";
    }

    // Telefon numarası 1 doğrulama
    if (empty($phone_number_1)) {
        $errors['phone_number_1'] = "Telefon numarası 1 alanı boş bırakılamaz.";
    } elseif (!preg_match('/^\+?[0-9]{10,15}$/', $phone_number_1)) {
        $errors['phone_number_1'] = "Geçersiz telefon numarası formatı.";
    }

    // Telefon numarası 2 doğrulama (opsiyonel)
    if (!empty($phone_number_2) && !preg_match('/^\+?[0-9]{10,15}$/', $phone_number_2)) {
        $errors['phone_number_2'] = "Geçersiz telefon numarası formatı.";
    }

    // Hatalar yoksa veritabanı işlemlerini gerçekleştir
    if (empty($errors)) {
        try {
            $dbh->beginTransaction();

            // Kullanıcıyı ekle
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $user_sql = "INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
            $stmt = $dbh->prepare($user_sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
			$stmt->bindParam(':role', $role);

            $stmt->execute();

            $user_id = $dbh->lastInsertId();

            // Öğretmen bilgilerini ekle
            $teacher_sql = "INSERT INTO teachers (user_id, branch, additional_branch, birth_date, phone_number_1, phone_number_2) 
                            VALUES (:user_id, :branch, :additional_branch, :birth_date, :phone_number_1, :phone_number_2)";
            $stmt = $dbh->prepare($teacher_sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':additional_branch', $additional_branch);
            $stmt->bindParam(':birth_date', $birth_date);
            $stmt->bindParam(':phone_number_1', $phone_number_1);
            $stmt->bindParam(':phone_number_2', $phone_number_2);
            $stmt->execute();

            $dbh->commit();

            // E-posta gönderme işlemi
            $to = $email;
            $subject = 'Ihr neues Passwort';
            $message = "Sehr geehrte/r Frau/Herr $surname,\n\n";
            $message .= "Um Ihr neues Passwort festzulegen, verwenden Sie dieses Passwort: $password\n\n";
            $message .= "Oder den untenstehenden Link nutzen:\n";
            $message .= "http://yourdomain.com/reset_password.php?token=" . urlencode(base64_encode($user_id)) . "\n\n";
            $message .= "Der Link ist 24 Stunden gültig und kann nur einmal verwendet werden.\n\n";
            $message .= "Herr .....\nSchulleiter";

            $headers = "From: admin@gs-hünxe.de";

            mail($to, $subject, $message, $headers);

            $message = "<div style='color: green;'>Öğretmen başarıyla eklendi ve e-posta gönderildi.</div>";
            $show_form = false; // Formu gizle

        } catch (PDOException $e) {
            $dbh->rollBack();
            $errors['general'] = "Veritabanı hatası: " . $e->getMessage();
        }
    }
} else {
    // Form ilk kez yüklendiğinde rastgele şifre oluştur
    $password = generateRandomPassword();
}
?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öğretmen Ekle</title>
    <link rel="stylesheet" href="../css/form_add_teacher.css">
    <style>
.teacher-form {
    display: grid;
    gap: 15px;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.teacher-form .form-group {
    display: flex;
    flex-direction: column;
}

.teacher-form label {
    margin-bottom: 5px;
    font-weight: bold;
}

.teacher-form input[type="text"],
.teacher-form input[type="email"],
.teacher-form input[type="tel"],
.teacher-form input[type="date"],
.teacher-form input[type="password"] {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.teacher-form .checkbox-group {
    display: flex;
    flex-direction: column;
}

.teacher-form .checkbox-group label {
    margin-bottom: 5px;
}

.btn-submit {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #0056b3;
}

        .error-tooltip {
            color: #ff0000;
            font-size: 12px;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .general-error {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (!empty($message)) {
            echo "<div class='success-message'>{$message}</div>";
        }

        if (isset($errors['general'])) {
            echo "<div class='general-error'>{$errors['general']}</div>";
        }
        ?>

        <?php if ($show_form): ?>
        <form action="" method="POST" class="teacher-form">
            <div class="form-group">
                <label for="name">Adı</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="error-tooltip"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="surname">Soyadı</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
                <?php if (isset($errors['surname'])): ?>
                    <div class="error-tooltip"><?php echo $errors['surname']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">E-posta Adresi</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error-tooltip"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="branch">Branşı</label>
                <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($branch ?? ''); ?>" required>
                <?php if (isset($errors['branch'])): ?>
                    <div class="error-tooltip"><?php echo $errors['branch']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="additional_branch">Ek Branşı (Opsiyonel)</label>
                <input type="text" id="additional_branch" name="additional_branch" value="<?php echo htmlspecialchars($additional_branch ?? ''); ?>">
                <?php if (isset($errors['additional_branch'])): ?>
                    <div class="error-tooltip"><?php echo $errors['additional_branch']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="birth_date">Doğum Tarihi</label>
                <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($birth_date ?? ''); ?>" required>
                <?php if (isset($errors['birth_date'])): ?>
                    <div class="error-tooltip"><?php echo $errors['birth_date']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="phone_number_1">İletişim Numarası 1</label>
                <input type="tel" id="phone_number_1" name="phone_number_1" value="<?php echo htmlspecialchars($phone_number_1 ?? ''); ?>" required>
                <?php if (isset($errors['phone_number_1'])): ?>
                    <div class="error-tooltip"><?php echo $errors['phone_number_1']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="phone_number_2">İletişim Numarası 2 (Opsiyonel)</label>
                <input type="tel" id="phone_number_2" name="phone_number_2" value="<?php echo htmlspecialchars($phone_number_2 ?? ''); ?>">
                <?php if (isset($errors['phone_number_2'])): ?>
                    <div class="error-tooltip"><?php echo $errors['phone_number_2']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" readonly>
                <small>Bu şifre öğretmenin giriş yapması için kullanılacaktır.</small>
            </div>

            <button type="submit" class="btn-submit">Öğretmeni Ekle</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Hata raporlamayı etkinleştir
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantısı
require '../pages/vt.php';

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

// Başlangıçta formu göstermek istiyoruz
$show_form = true;
$generatedPassword = ''; // Şifreyi saklamak için
$message = ''; // Uyarı mesajlarını tutmak için

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al ve güvenli hale getir
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $branch = trim($_POST['branch']);
    $additional_branch = isset($_POST['additional_branch']) ? trim($_POST['additional_branch']) : null;
    $birth_date = trim($_POST['birth_date']);
    $phone_number_1 = trim($_POST['phone_number_1']);
    $phone_number_2 = isset($_POST['phone_number_2']) ? trim($_POST['phone_number_2']) : null;
    $password = trim($_POST['password']);

    // E-posta adresinin geçerli olup olmadığını kontrol et
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div style='color: red;'>Geçersiz e-posta adresi.</div>";
    } else {
        // E-posta adresinin veritabanında olup olmadığını kontrol et
        try {
            $email_check_sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $dbh->prepare($email_check_sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $email_exists = $stmt->fetchColumn();

            if ($email_exists) {
                // E-posta adresi zaten mevcut
                $message = "<div style='color: red;'>Bu email adresi sistemde kayıtlı. Lütfen farklı bir email adresi kullanın.</div>";
            } else {
                // E-posta adresi mevcut değil, kullanıcıyı ekle
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Kullanıcıyı ekle
                $user_sql = "INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
                $stmt = $dbh->prepare($user_sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':surname', $surname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password_hash);

                if ($stmt->execute()) {
                    $user_id = $dbh->lastInsertId();

                    // Öğretmen bilgilerini ekle
                    $teacher_sql = "INSERT INTO teachers (user_id, branch, additional_branch, birth_date, phone_number_1, phone_number_2) VALUES (:user_id, :branch, :additional_branch, :birth_date, :phone_number_1, :phone_number_2)";
                    $stmt = $dbh->prepare($teacher_sql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':branch', $branch);
                    $stmt->bindParam(':additional_branch', $additional_branch);
                    $stmt->bindParam(':birth_date', $birth_date);
                    $stmt->bindParam(':phone_number_1', $phone_number_1);
                    $stmt->bindParam(':phone_number_2', $phone_number_2);

                    if ($stmt->execute()) {
                        $message = "<div style='color: green;'>Veri girişi başarıyla tamamlandı.</div>";
                        // Formu gizlemek
                        $show_form = false;
                    } else {
                        $message = "<div style='color: red;'>Öğretmen eklenirken bir hata oluştu.</div>";
                    }
                } else {
                    $message = "<div style='color: red;'>Kullanıcı eklenirken bir hata oluştu.</div>";
                }
            }
        } catch (PDOException $e) {
            $message = "<div style='color: red;'>Veritabanı hatası: " . $e->getMessage() . "</div>";
        }
    }
} else {
    // Form ilk kez yüklendiğinde rastgele şifre oluştur
    $generatedPassword = generateRandomPassword();
}

?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Öğretmen Ekle</title>
	
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

	</style>
<!-- <link rel="stylesheet" href="../css/form_add_teacher.css"> -->
</head>
<body>
    <?php
    // Uyarı mesajını göster
    if ($message) {
        echo $message;
    }
    ?>

    <?php if ($show_form): ?>
    <form action="" method="POST" class="teacher-form">
        <div class="form-group">
            <label for="surname">Soyadı</label>
            <input type="text" id="surname" name="surname" required>
        </div>
        <div class="form-group">
            <label for="name">Adı</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">E-posta Adresi</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="branch">Branşı</label>
            <input type="text" id="branch" name="branch" required>
        </div>
        <div class="form-group">
            <label for="additional_branch">Ek Branşı (opsiyonel)</label>
            <input type="text" id="additional_branch" name="additional_branch">
        </div>
        <div class="form-group">
            <label for="birth_date">Doğum Tarihi</label>
            <input type="date" id="birth_date" name="birth_date" required>
        </div>
        <div class="form-group">
            <label for="phone_number_1">İletişim Numarası 1</label>
            <input type="tel" id="phone_number_1" name="phone_number_1" required>
        </div>
        <div class="form-group">
            <label for="phone_number_2">İletişim Numarası 2 (opsiyonel)</label>
            <input type="tel" id="phone_number_2" name="phone_number_2">
        </div>
        <div class="form-group">
            <label for="password">Şifre</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($generatedPassword); ?>" readonly>
        </div>
        <button type="submit" class="btn-submit">Öğretmeni Ekle</button>
    </form>
    <?php endif; ?>
	<script>
document.addEventListener('DOMContentLoaded', function() {
    const teacherForm = document.querySelector('.teacher-form');
    
    if (teacherForm) {
        teacherForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Formun varsayılan gönderimini engelle

            // Form verilerini topla
            let formData = new FormData(this);

            // Fetch API ile form verilerini gönder
            fetch('form_add_teacher.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                document.getElementById('content-area').innerHTML = result;
            })
            .catch(error => {
                console.error('Hata:', error);
            });
        });
    }
});
</script>

</body>
</html>
                
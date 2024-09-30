<?php
require '../pages/functions.php';

session_start();

if (!checkToken()) {
    echo "<div id='content'><p>Yetkisiz erişim. Lütfen oturum açın.</p></div>";
    header("Location: ../pages/user_login.php");
    exit();
}

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleFormSubmission();
} else {
    $password = generateRandomPassword(); 
}

function handleFormSubmission() {
    global $dbh, $errors, $message;

    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $additional_branch = trim($_POST['additional_branch'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $phone_number_1 = trim($_POST['phone_number_1'] ?? '');
    $phone_number_2 = trim($_POST['phone_number_2'] ?? '');
    $password = $_POST['password'] ?? generateRandomPassword();
    $role = 'teacher';

    if (empty($name)) $errors['name'] = "İsim alanı boş bırakılamaz.";
    if (empty($surname)) $errors['surname'] = "Soyisim alanı boş bırakılamaz.";
    if (empty($email)) $errors['email'] = "E-posta alanı boş bırakılamaz.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Geçersiz e-posta adresi.";

    if (empty($errors)) {
        try {
            $dbh->beginTransaction();

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("INSERT INTO users (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)");
            $stmt->execute([':name' => $name, ':surname' => $surname, ':email' => $email, ':password' => $password_hash, ':role' => $role]);

            $user_id = $dbh->lastInsertId();

            $stmt = $dbh->prepare("INSERT INTO teachers (user_id, branch, additional_branch, birth_date, phone_number_1, phone_number_2, email) 
                                    VALUES (:user_id, :branch, :additional_branch, :birth_date, :phone_number_1, :phone_number_2, :email)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':branch' => $branch,
                ':additional_branch' => $additional_branch,
                ':birth_date' => $birth_date,
                ':phone_number_1' => $phone_number_1,
                ':phone_number_2' => $phone_number_2,
                ':email' => $email
            ]);

            $dbh->commit();

            if (sendTeacherEmail($email, $surname)) {
                $message = "<div style='color: green;'>Öğretmen başarıyla eklendi ve e-posta gönderildi.</div>";
            } else {
                $errors['general'] = "Öğretmen eklendi, ancak e-posta gönderilemedi.";
            }
        } catch (PDOException $e) {
            $dbh->rollBack();
            $errors['general'] = "Veritabanı hatası: " . $e->getMessage();
        }
    }

    if (isset($_POST['api']) && $_POST['api'] === 'true') {
        if (empty($errors)) {
            echo json_encode(['status' => 'success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Bir hata oluştu.', 'errors' => $errors]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
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
</head>
<body>
    <div id="content">
        <h2>Öğretmen Ekle</h2>

        <?php
        if (!empty($message)) {
            echo $message;
        }

        if (!empty($errors)) {
            echo "<div style='color: red;'><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul></div>";
        }
        ?>

        <form action="" method="POST" class="teacher-form">
            <div class="form-group">
                <label for="name">Adı:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="surname">Soyadı:</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-posta:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="branch">Branşı:</label>
                <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($branch ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="additional_branch">Ek Branşı (Opsiyonel):</label>
                <input type="text" id="additional_branch" name="additional_branch" value="<?php echo htmlspecialchars($additional_branch ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="birth_date">Doğum Tarihi:</label>
                <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($birth_date ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number_1">İletişim Numarası 1:</label>
                <input type="tel" id="phone_number_1" name="phone_number_1" value="<?php echo htmlspecialchars($phone_number_1 ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number_2">İletişim Numarası 2 (Opsiyonel):</label>
                <input type="tel" id="phone_number_2" name="phone_number_2" value="<?php echo htmlspecialchars($phone_number_2 ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" readonly>
            </div>
            <button type="submit" class="btn-submit">Öğretmeni Ekle</button>
        </form>
    </div>
</body>
</html>

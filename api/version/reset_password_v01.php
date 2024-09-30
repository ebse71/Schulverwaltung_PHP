<?php
require '../pages/functions.php'; 

// Token'ı ve e-postayı doğrula
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Token'ı `password_resets` tablosunda ara
    $stmt = $dbh->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $email = $stmt->fetchColumn();

    if ($email) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                if (isStrongPassword($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    $stmt = $dbh->prepare("UPDATE users SET password = :password WHERE email = :email");
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    $stmt = $dbh->prepare("DELETE FROM password_resets WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    header("Location: ../pages/user_login.php");
                    exit();
                } else {
                    echo "<div style='color: red;'>Şifre en az 8 karakter uzunluğunda olmalı, bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.</div>";
                }
            } else {
                echo "<div style='color: red;'>Şifreler eşleşmiyor. Lütfen tekrar deneyin.</div>";
            }
        }
    } else {
        echo "<div style='color: red;'>Geçersiz veya süresi dolmuş token.</div>";
        exit();
    }
} else {
    echo "<div style='color: red;'>Token eksik.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama</title>
    <link rel="stylesheet" href="../css/reset_password.css">
</head>
<body>
    <div class="container">
        <h2>Yeni Şifre Belirle</h2>
        <form method="POST">
            <div class="form-group">
                <label for="new_password">Yeni Şifre:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Yeni Şifre (Tekrar):</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit">Şifreyi Değiştir</button>
        </form>
    </div>
</body>
</html>

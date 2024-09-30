<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vt.php';

session_start(); // Oturum başlatma her dosyada en başta olmalı

function checkToken() {
    global $dbh;

    if (isset($_COOKIE['user_token'])) {
        $token = $_COOKIE['user_token'];
        $stmt = $dbh->prepare("SELECT * FROM users WHERE userkey = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Oturum süresini kontrol et
            if (!isset($_SESSION['last_activity']) || (time() - $_SESSION['last_activity']) > 300) {
                session_unset();
                session_destroy();
                setcookie('user_token', '', time() - 3600, "/"); // Token çerezini sil
                return false;
            }

            // Son etkinlik zamanını güncelle
            $_SESSION['last_activity'] = time();
            return true;
        }
    }

    return false;
}

function generateToken() {
    return bin2hex(random_bytes(32)); // Rastgele token üretir
}
function sendTeacherEmail($email, $surname) {
    require '../vendor/autoload.php';

    // Token oluşturma
    $resetToken = generateToken();
    $resetLink = "https://gshuenxe.de/api/reset_password.php?token=" . urlencode($resetToken);

    // Token'ı veritabanına kaydet
    global $dbh;
    $stmt = $dbh->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
    $stmt->execute([
        ':email' => $email,
        ':token' => $resetToken,
        ':expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
    ]);

    // E-posta gönderimi
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.strato.de';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@gshuenxe.de';
        $mail->Password = 'Bekir1976.';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('info@gshuenxe.de', 'Schulleiter');
        $mail->addAddress($email);

        $mail->isHTML(false);
        $mail->Subject = 'Ihr neues Passwort';
        $mail->Body = "Sehr geehrte/r Frau/Herr $surname,\n\n";
        $mail->Body .= "Um Ihr neues Passwort festzulegen, verwenden Sie diesen Link:\n";
        $mail->Body .= "$resetLink\n\n";
        $mail->Body .= "Der Link ist 24 Stunden gültig und kann nur einmal verwendet werden.\n\n";
        $mail->Body .= "Herr .....\nSchulleiter";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

function isStrongPassword($password) {
    // Şifrenin uzunluğu en az 8 karakter olmalı, büyük harf, küçük harf, rakam ve özel karakter içermeli
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password); // \W = non-word characters (Özel karakterler)
}
function sendPasswordResetEmail($email, $token) {
    $resetLink = "https://gshuenxe.de/pages/reset_password.php?token=" . urlencode($token);
    
    // E-posta gönderme işlemi
    $subject = "Şifre Sıfırlama İsteği";
    $message = "Merhaba,\n\nŞifrenizi sıfırlamak için aşağıdaki linke tıklayın:\n" . $resetLink;
    $headers = "From: no-reply@gshuenxe.de";

    mail($email, $subject, $message, $headers);
}

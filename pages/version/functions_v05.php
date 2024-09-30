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

    $resetToken = generateToken();
    $resetLink = "https://gshuenxe.de/api/reset_password.php?token=" . urlencode($resetToken);

    global $dbh;
    $stmt = $dbh->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
    $stmt->execute([
        ':email' => $email,
        ':token' => $resetToken,
        ':expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
    ]);

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
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password);
}

function sendPasswordResetEmail($email, $token) {
    $resetLink = "https://gshuenxe.de/api/reset_password.php?token=" . urlencode($token);
    
    $subject = "Şifre Sıfırlama İsteği";
    $message = "Merhaba,\n\nŞifrenizi sıfırlamak için aşağıdaki linke tıklayın:\n" . $resetLink;
    $headers = "From: no-reply@gshuenxe.de";

    mail($email, $subject, $message, $headers);
}
?>

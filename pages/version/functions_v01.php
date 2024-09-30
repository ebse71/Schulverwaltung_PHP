<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vt.php';
function checkToken() {
    global $dbh;

    if (isset($_COOKIE['user_token'])) {
        $token = $_COOKIE['user_token'];
        $parts = explode("_", $token);
        $user_id = $parts[0];

        // Veritabanında userkey'i kontrol et
        $query = "SELECT userkey FROM users WHERE user_id = :user_id";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if ($token === $user_id . "_" . $row['userkey']) {
                return true;
            } else {
                echo "Token uyuşmazlığı!"; // Debug için
            }
        } else {
            echo "Kullanıcı bulunamadı!"; // Debug için
        }
    } else {
        echo "Token bulunamadı!"; // Debug için
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
    $resetLink = "https://gshuenxe.de/pages/reset_password.php?token=" . urlencode($resetToken);

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
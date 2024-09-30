<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Sunucu ayarları
    $mail->isSMTP();
    $mail->Host       = 'smtp.strato.de';  // SMTP sunucu adresi
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@gshuenxe.de';  // SMTP kullanıcı adı
    $mail->Password   = 'Bekir1976.';  // SMTP şifresi
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;  // SMTP portu
	
    // Alıcılar
    $mail->setFrom('info@gshuenxe.de', 'Test');
    $mail->addAddress('khkliyiz@gmail.com');  // Kendi e-posta adresinizi girin

    // İçerik
    $mail->isHTML(true);
    $mail->Subject = 'SMTP Test';
    $mail->Body    = 'Bu, SMTP ayarlarını test etmek için gönderilen bir test e-postasıdır.';

    $mail->send();
    echo 'E-posta başarıyla gönderildi';
} catch (Exception $e) {
    echo "E-posta gönderilemedi. Hata: {$mail->ErrorInfo}";
}

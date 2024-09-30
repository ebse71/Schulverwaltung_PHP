<?php

require '../pages/functions.php';
require '../pages/vt.php';

header('Content-Type: application/json'); // JSON yanıtı olarak döneceğimizi belirtelim

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz e-posta adresi.']);
        exit();
    }

    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Daha önce giriş yapılıp yapılmadığını kontrol et
        if ($user['last_activity'] === null) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lütfen ilk giriş için Admin\'in gönderdiği linki ya da şifreyi kullanın.',
                'redirect' => '../pages/user_login.php'
            ]);
            exit();
        }

        $resetToken = generateToken();
        $stmt = $dbh->prepare("UPDATE users SET reset_token = :token WHERE user_id = :id");
        $stmt->execute([':token' => $resetToken, ':id' => $user['user_id']]);

        sendPasswordResetEmail($email, $resetToken);

        echo json_encode(['status' => 'success']);
        exit(); // Başarı durumunda da çıkış yapmayı unutmayın
    } else {
        echo json_encode(['status' => 'success']); // Sistemimizde yoksa bile aynı mesaj gösteriyoruz.
        exit();
    }
}
?>

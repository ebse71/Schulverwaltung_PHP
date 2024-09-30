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
        
        if ($user['userkey'] === null) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lütfen ilk giriş için Admin\'in gönderdiği linki ya da şifreyi kullanın.',
                'redirect' => '../pages/user_login.php'
            ]);
            exit();
        }

        $resetToken = generateToken();
        $stmt = $dbh->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
        $stmt->execute([':email' => $email, ':token' => $resetToken, ':expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))]);

        sendPasswordResetEmail($email, $resetToken);

        // Kullanıcı aktivitesini kaydet
        $stmt = $dbh->prepare("INSERT INTO user_activity_logs (user_id, activity_type, activity_details) VALUES (:user_id, 'password_reset_request', 'Password reset link sent')");
        $stmt->execute([':user_id' => $user['user_id']]);

        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'success']);
        exit();
    }
}
?>

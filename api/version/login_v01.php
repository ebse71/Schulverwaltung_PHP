<?php

session_start();
require '../pages/functions.php';
require '../pages/vt.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (password_verify($password, $user['password'])) {
            $token = generateToken();
            $stmt = $dbh->prepare("UPDATE users SET userkey = :token WHERE user_id = :id");
            $stmt->execute([':token' => $token, ':id' => $user['user_id']]);
    
            setcookie('user_token', $token, time() + (86400 * 30), "/"); // 30 gün geçerli
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['last_activity'] = time(); // Son etkinlik zamanını güncelle
            header("Location: ../pages/admin.php");
            exit();
        } else {
            $_SESSION['error'] = "Yanlış şifre.";
        }
    } else {
        $_SESSION['error'] = "Yanlış kullanıcı adı.";
    }
}    
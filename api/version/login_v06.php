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
            
            // Kullanıcı aktivitesini kaydet
            $stmt = $dbh->prepare("INSERT INTO user_activity_logs (user_id, activity_type, activity_details) VALUES (:user_id, 'login', 'User logged in')");
            $stmt->execute([':user_id' => $user['user_id']]);

            setcookie('user_token', $token, time() + (86400 * 30), "/"); // 30 gün geçerli
            $_SESSION['user_id'] = $user['user_id'];

            echo json_encode(['status' => 'success', 'redirect' => '../pages/admin.php']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Yanlış kullanici adi ya da şifre.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Yanlış kullanici adi ya da şifre.']);
        exit();
    }
}
?>

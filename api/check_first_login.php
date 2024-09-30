<?php
require '../pages/vt.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email'])) {
    $email = $data['email'];

    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['first_login'] === null) { // Eğer first_login null ise, kullanıcı henüz başarılı giriş yapmamış demektir
            echo json_encode(['status' => 'error', 'message' => 'Lütfen ilk giriş için Admin\'in gönderdiği linki ya da şifreyi kullanın.']);
            exit();
        } else {
            echo json_encode(['status' => 'success', 'redirect' => '../pages/reset_link.php']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Bu e-posta adresi kayıtlı değil.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'E-posta adresi girilmedi.']);
}

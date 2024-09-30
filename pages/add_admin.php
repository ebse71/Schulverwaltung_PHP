<?php
$dsn = 'mysql:host=rdbms.strato.de;dbname=dbs13234765';
$benutzername = 'dbu5465793';
$passwort = 'Bekir1976.';

try {
    // Veritabanı bağlantısını oluştur
    $dbh = new PDO($dsn, $benutzername, $passwort);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Şifreyi hash'leyin
    $hashedPassword = password_hash('Admin2024.', PASSWORD_DEFAULT);

    // Admin için token oluştur
    $token = bin2hex(random_bytes(32));

    // Admin kullanıcısını ekleyin ve token'ı kaydedin
    $stmt = $dbh->prepare("INSERT INTO users (name, surname, email, password, role, userkey) 
                           VALUES (:name, :surname, :email, :password, :role, :userkey)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':userkey', $token);

    // Admin bilgileri
    $name = 'admin';
    $surname = 'admin';
    $email = 'admin@gshuenxe.de';
    $role = 'admin';

    // Sorguyu çalıştır
    $stmt->execute();

    // Admin kullanıcı için token'ı çerez olarak ayarlayın
    setcookie('user_token', $dbh->lastInsertId() . "_" . $token, time() + (86400 * 30), "/"); // 30 gün geçerli olacak şekilde ayarlanır

    echo "Admin kullanıcı başarıyla eklendi, token oluşturuldu ve çerez ayarlandı!";
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

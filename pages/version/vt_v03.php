<?php
$dsn = 'mysql:host=rdbms.strato.de;port=22;dbname=dbs13234765';
$benutzername = 'dbu5465793';
$passwort = 'Bekir1976.';

try {
    // Veritabanı bağlantısını oluştur
    $dbh = new PDO($dsn, $benutzername, $passwort);
    // PDO hata modunu ayarla
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Verbindung zur Datenbank hergestellt."; // Bu satırı kaldırın
} catch (PDOException $e) {
    // Hata durumunda mesaj yazdır
    die("Fehler bei der Verbindung zur Datenbank: " . $e->getMessage());
}
?>

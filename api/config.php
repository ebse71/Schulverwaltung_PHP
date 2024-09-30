<?php
$dsn = 'mysql:host=rdbms.strato.de;port=22;dbname=dbs13234765';
$benutzername = 'dbu5465793';
$passwort = 'Bekir1976.';

try {
    $dbh = new PDO($dsn, $benutzername, $passwort);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>

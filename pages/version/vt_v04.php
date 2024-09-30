<?php
$dsn = 'mysql:host=rdbms.strato.de;port=22;dbname=dbs13234765';
$benutzername = 'dbu5465793';
$passwort = 'Bekir1976.';

try {
    // Datenbankverbindung herstellen
    $dbh = new PDO($dsn, $benutzername, $passwort);
    // PDO-Fehlermodus setzen
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Verbindung zur Datenbank hergestellt."; // Diese Zeile entfernen
} catch (PDOException $e) {
    // Fehler bei der Verbindung ausgeben
    die("Fehler bei der Verbindung zur Datenbank: " . $e->getMessage());
}

<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $dbh->query("SELECT * FROM teachers");
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'data' => $teachers]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

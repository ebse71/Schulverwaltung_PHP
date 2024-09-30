<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'ID gerekli.']);
        exit;
    }

    try {
        $stmt = $dbh->prepare("DELETE FROM teachers WHERE user_id = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'Öğretmen silindi.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

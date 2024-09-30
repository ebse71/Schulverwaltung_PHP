<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);

    $id = $put_vars['id'] ?? '';
    $name = $put_vars['name'] ?? '';
    $surname = $put_vars['surname'] ?? '';
    $email = $put_vars['email'] ?? '';
    $branch = $put_vars['branch'] ?? '';
    $additional_branch = $put_vars['additional_branch'] ?? '';
    $birth_date = $put_vars['birth_date'] ?? '';
    $phone_number_1 = $put_vars['phone_number_1'] ?? '';
    $phone_number_2 = $put_vars['phone_number_2'] ?? '';

    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'ID gerekli.']);
        exit;
    }

    try {
        $stmt = $dbh->prepare("UPDATE teachers SET name = :name, surname = :surname, branch = :branch, additional_branch = :additional_branch, birth_date = :birth_date, phone_number_1 = :phone_number_1, phone_number_2 = :phone_number_2 WHERE user_id = :id");
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':surname' => $surname,
            ':branch' => $branch,
            ':additional_branch' => $additional_branch,
            ':birth_date' => $birth_date,
            ':phone_number_1' => $phone_number_1,
            ':phone_number_2' => $phone_number_2
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Öğretmen güncellendi.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
}

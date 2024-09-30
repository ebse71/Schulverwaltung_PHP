<?php
// Fehlerberichterstattung aktivieren
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Datenbankverbindung
require '../pages/vt.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formularwerte übernehmen
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $additional_branch = trim($_POST['additional_branch'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $phone_number_1 = trim($_POST['phone_number_1'] ?? '');
    $phone_number_2 = trim($_POST['phone_number_2'] ?? '');
    $password = $_POST['password'] ?? generateRandomPassword();

    // Überprüfen, ob die E-Mail-Adresse bereits in der Datenbank vorhanden ist
    $email_check_sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $dbh->prepare($email_check_sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $email_exists = $stmt->fetchColumn();

    if ($email_exists) {
        // E-Mail-Adresse bereits vorhanden
        header("Location: form_add_teacher.php?status=email_exists");
        exit();
    } else {
        // E-Mail-Adresse nicht vorhanden, Benutzer hinzufügen
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $dbh->beginTransaction();
            $role = 'teacher'; 
            $user_sql = "INSERT INTO users (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)";
            $stmt = $dbh->prepare($user_sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':role',  $role);
            $stmt->execute();

            $user_id = $dbh->lastInsertId();

            $teacher_sql = "INSERT INTO teachers (user_id, branch, additional_branch, birth_date, phone_number_1, phone_number_2) 
                            VALUES (:user_id, :branch, :additional_branch, :birth_date, :phone_number_1, :phone_number_2)";
            $stmt = $dbh->prepare($teacher_sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':additional_branch', $additional_branch);
            $stmt->bindParam(':birth_date', $birth_date);
            $stmt->bindParam(':phone_number_1', $phone_number_1);
            $stmt->bindParam(':phone_number_2', $phone_number_2);
            $stmt->execute();

            $dbh->commit();

            // E-Mail senden
            $to = $email;
            $subject = 'Ihr neues Passwort';
            $message = "Sehr geehrte/r Frau/Herr $surname,\n\n";
            $message .= "Um Ihr neues Passwort festzulegen, verwenden Sie dieses Passwort: $password\n\n";
            $message .= "Oder nutzen Sie den folgenden Link:\n";
            $message .= "http://gshuenxe.de/api/reset_password.php?token=" . urlencode(base64_encode($user_id)) . "\n\n";
            $message .= "Der Link ist 24 Stunden gültig und kann nur einmal verwendet werden.\n\n";
            $message .= "Herr .....\nSchulleiter";

            $headers = "From: admin@gs-hünxe.de";

            mail($to, $subject, $message, $headers);

            header("Location: form_add_teacher.php?status=success");
            exit();

        } catch (PDOException $e) {
            $dbh->rollBack();
            header("Location: form_add_teacher.php?status=error");
            exit();
        }
    }
}
?>

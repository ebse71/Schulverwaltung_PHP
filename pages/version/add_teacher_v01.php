<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../pages/vt.php';

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $branch = $_POST['branch'];
    $additional_branch = $_POST['additional_branch'] ?? null;
    $birth_date = $_POST['birth_date'];
    $phone_number_1 = $_POST['phone_number_1'];
    $phone_number_2 = $_POST['phone_number_2'] ?? null;
    $password = $_POST['password'];

    // E-posta adresinin veritabanında olup olmadığını kontrol et
    $email_check_sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $dbh->prepare($email_check_sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $email_exists = $stmt->fetchColumn();

    if ($email_exists) {
        // E-posta adresi zaten mevcut
        header("Location: ../pages/form_add_teacher.php?status=email_exists");
        exit();
    } else {
        // E-posta adresi mevcut değil, kullanıcıyı ekle
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $user_sql = "INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
            $stmt = $dbh->prepare($user_sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);

            if ($stmt->execute()) {
                $user_id = $dbh->lastInsertId();

                $teacher_sql = "INSERT INTO teachers (user_id, branch, additional_branch, birth_date, phone_number_1, phone_number_2) VALUES (:user_id, :branch, :additional_branch, :birth_date, :phone_number_1, :phone_number_2)";
                $stmt = $dbh->prepare($teacher_sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':branch', $branch);
                $stmt->bindParam(':additional_branch', $additional_branch);
                $stmt->bindParam(':birth_date', $birth_date);
                $stmt->bindParam(':phone_number_1', $phone_number_1);
                $stmt->bindParam(':phone_number_2', $phone_number_2);

                if ($stmt->execute()) {
                    // E-posta gönderme işlemi
                    $to = $email;
                    $subject = 'Ihr neues Passwort';
                    $message = "Sehr geehrte/r Frau/Herr $surname,\n\n";
                    $message .= "Um Ihr neues Passwort festzulegen, verwenden Sie dieses Passwort: $password\n\n";
                    $message .= "Oder den untenstehenden Link nutzen:\n";
                    $message .= "http://yourdomain.com/reset_password.php?token=" . urlencode(base64_encode($user_id)) . "\n\n";
                    $message .= "Der Link ist 24 Stunden gültig und kann nur einmal verwendet werden.\n\n";
                    $message .= "Herr .....\nSchulleiter";

                    $headers = "From: admin@gs-hünxe.de";

                    mail($to, $subject, $message, $headers);

                    header("Location: ../pages/form_add_teacher.php?status=success");
                    exit();
                } else {
                    header("Location: ../pages/form_add_teacher.php?status=error");
                    exit();
                }
            } else {
                header("Location: ../pages/form_add_teacher.php?status=error");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: ../pages/form_add_teacher.php?status=error");
            exit();
        }
    }
}

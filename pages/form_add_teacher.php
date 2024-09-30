<?php
require '../pages/functions.php';

session_start();

if (!checkToken()) {
    echo "<div id='content'><p>Unbefugter Zugriff. Bitte melden Sie sich an.</p></div>";
    header("Location: ../pages/user_login.php");
    exit();
}

$errors = [];
$message = '';
$name = $surname = $email = $branch = $additional_branch = $birth_date = $phone_number_1 = $phone_number_2 = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleFormSubmission();
} else {
    $password = generateRandomPassword(); 
}

function handleFormSubmission() {
    global $dbh, $errors, $message, $name, $surname, $email, $branch, $additional_branch, $birth_date, $phone_number_1, $phone_number_2;

    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $additional_branch = trim($_POST['additional_branch'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $phone_number_1 = trim($_POST['phone_number_1'] ?? '');
    $phone_number_2 = trim($_POST['phone_number_2'] ?? '');
    $password = $_POST['password'] ?? generateRandomPassword();
    $role = 'teacher';

    if (empty($name)) $errors['name'] = "Das Namensfeld darf nicht leer sein.";
    if (empty($surname)) $errors['surname'] = "Das Nachnamenfeld darf nicht leer sein.";
    if (empty($email)) $errors['email'] = "Das E-Mail-Feld darf nicht leer sein.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Ungültige E-Mail-Adresse.";

    if (empty($errors)) {
        try {
            $dbh->beginTransaction();

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("INSERT INTO users (name, surname, email, password, role) VALUES (:name, :surname, :email, :password, :role)");
            $stmt->execute([':name' => $name, ':surname' => $surname, ':email' => $email, ':password' => $password_hash, ':role' => $role]);

            $user_id = $dbh->lastInsertId();

            $stmt = $dbh->prepare("INSERT INTO teachers (user_id, branch, additional_branch, birth_date, phone_number_1, phone_number_2, email) 
                                    VALUES (:user_id, :branch, :additional_branch, :birth_date, :phone_number_1, :phone_number_2, :email)");
            $stmt->execute([
                ':user_id' => $user_id,
                ':branch' => $branch,
                ':additional_branch' => $additional_branch,
                ':birth_date' => $birth_date,
                ':phone_number_1' => $phone_number_1,
                ':phone_number_2' => $phone_number_2,
                ':email' => $email
            ]);

            $dbh->commit();

            if (sendTeacherEmail($email, $surname)) {
                $message = "<div style='color: green;'>Lehrer erfolgreich hinzugefügt und E-Mail gesendet.</div>";
            } else {
                $errors['general'] = "Lehrer hinzugefügt, aber E-Mail konnte nicht gesendet werden.";
            }
        } catch (PDOException $e) {
            $dbh->rollBack();
            $errors['general'] = "Datenbankfehler: Diese E-Mail-Adresse ist bereits im System registriert.";
        }
    }

    if (isset($_POST['api']) && $_POST['api'] === 'true') {
        if (empty($errors)) {
            echo json_encode(['status' => 'success', 'message' => $message]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ein Fehler ist aufgetreten.', 'errors' => $errors]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lehrer Hinzufügen</title>
    <style>
        .teacher-form {
            display: grid;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .teacher-form .form-group {
            display: flex;
            flex-direction: column;
        }

        .teacher-form label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .teacher-form input[type="text"],
        .teacher-form input[type="email"],
        .teacher-form input[type="tel"],
        .teacher-form input[type="date"],
        .teacher-form input[type="password"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .teacher-form .checkbox-group {
            display: flex;
            flex-direction: column;
        }

        .teacher-form .checkbox-group label {
            margin-bottom: 5px;
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="content">
        <h2>Lehrer Hinzufügen</h2>

        <?php
        if (!empty($message)) {
            echo $message;
        }

        if (!empty($errors)) {
            echo "<div style='color: red;'><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul></div>";
        }
        ?>

<form action="" method="POST" class="teacher-form">
    <div class="form-group">
        <label for="name">Vorname:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    <div class="form-group">
        <label for="surname">Nachname:</label>
        <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="form-group">
        <label for="branch">Fachrichtung:</label>
        <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($branch); ?>" required>
    </div>
    <div class="form-group">
        <label for="additional_branch">Zusätzliches Fach (Optional):</label>
        <input type="text" id="additional_branch" name="additional_branch" value="<?php echo htmlspecialchars($additional_branch); ?>">
    </div>
    <div class="form-group">
        <label for="birth_date">Geburtsdatum:</label>
        <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($birth_date); ?>" required>
    </div>
    <div class="form-group">
        <label for="phone_number_1">Kontakt Nummer 1:</label>
        <input type="tel" id="phone_number_1" name="phone_number_1" value="<?php echo htmlspecialchars($phone_number_1); ?>" required>
    </div>
    <div class="form-group">
        <label for="phone_number_2">Kontakt Nummer 2 (Optional):</label>
        <input type="tel" id="phone_number_2" name="phone_number_2" value="<?php echo htmlspecialchars($phone_number_2); ?>">
    </div>
    <div class="form-group">
        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" readonly>
    </div>
    <button type="submit" class="btn-submit">Lehrer Hinzufügen</button>
</form>

    </div>
</body>
</html>

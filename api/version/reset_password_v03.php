<?php
require '../pages/functions.php'; 

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $dbh->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $email = $stmt->fetchColumn();

    if ($email) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                if (isStrongPassword($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    $stmt = $dbh->prepare("UPDATE users SET password = :password WHERE email = :email");
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    $stmt = $dbh->prepare("DELETE FROM password_resets WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    $stmt = $dbh->prepare("INSERT INTO user_activity_logs (user_id, activity_type, activity_details) VALUES ((SELECT user_id FROM users WHERE email = :email), 'password_reset', 'Password was reset')");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    header("Location: ../pages/user_login.php");
                    exit();
                } else {
                    echo "<div class='error'>Das Passwort muss mindestens 10 Zeichen lang sein, einen Großbuchstaben, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen enthalten.</div>";
                }
            } else {
                echo "<div class='error'>Die Passwörter stimmen nicht überein. Bitte versuchen Sie es erneut.</div>";
            }
        }
    } else {
        echo "<div class='error'>Ungültiger oder abgelaufener Token.</div>";
        exit();
    }
} else {
    echo "<div class='error'>Token fehlt.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort Zurücksetzen</title>
    <link rel="stylesheet" href="../css/reset_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    <h2>Neues Passwort Festlegen</h2>
    <form id="reset-password-form" method="POST">
        <div class="form-floating position-relative">
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder=" " required>
            <label for="new_password">Neues Passwort</label>
            <i class="fa fa-eye toggle-password" id="toggle-password1"></i>
        </div>
        <div class="form-floating position-relative">
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder=" " required>
            <label for="confirm_password">Neues Passwort (Wiederholen)</label>
            <i class="fa fa-eye toggle-password" id="toggle-password2"></i>
        </div>
        <div class="password-rules">
            <p id="length-rule" class="invalid">- Mindestens 10 Zeichen</p>
            <p id="lowercase-rule" class="invalid">- Kleinbuchstaben</p>
            <p id="uppercase-rule" class="invalid">- Großbuchstaben</p>
            <p id="symbol-rule" class="invalid">- Symbole</p>
            <p id="number-rule" class="invalid">- Zahlen</p>
            <p id="match-rule" class="invalid">- Gleiche Password</p>
        </div>
        <button type="submit" id="submit-button" disabled>Passwort Ändern</button>
    </form>
</div>

    <script>
document.querySelectorAll('.toggle-password').forEach(function (element) {
    element.addEventListener('click', function () {
        const passwordField = this.previousElementSibling.previousElementSibling;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
});

// Passwortregeln und Button-Status überprüfen
const passwordInput = document.getElementById('new_password');
const confirmPasswordInput = document.getElementById('confirm_password');
const submitButton = document.getElementById('submit-button');

function validatePassword() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    const lengthRule = document.getElementById('length-rule');
    const lowercaseRule = document.getElementById('lowercase-rule');
    const uppercaseRule = document.getElementById('uppercase-rule');
    const symbolRule = document.getElementById('symbol-rule');
    const numberRule = document.getElementById('number-rule');
    const matchRule = document.getElementById('match-rule');

    const rulesMet = {
        length: password.length >= 10,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password),
        number: /\d/.test(password),
        match: password === confirmPassword
    };

    lengthRule.className = rulesMet.length ? 'valid' : 'invalid';
    lowercaseRule.className = rulesMet.lowercase ? 'valid' : 'invalid';
    uppercaseRule.className = rulesMet.uppercase ? 'valid' : 'invalid';
    symbolRule.className = rulesMet.symbol ? 'valid' : 'invalid';
    numberRule.className = rulesMet.number ? 'valid' : 'invalid';
    matchRule.className = rulesMet.match ? 'valid' : 'invalid';

    const allValid = Object.values(rulesMet).every(Boolean);
    submitButton.disabled = !allValid;
}

passwordInput.addEventListener('input', validatePassword);
confirmPasswordInput.addEventListener('input', validatePassword);

    </script>
</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/user_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body id="login-page">
    <div class="container">
        <div class="left-side">
            <img src="../images/gsh_index_bold.png" alt="Görsel" class="login-image">
        </div>
        <div class="right-side">
            <div class="logo-container">
                <img src="../images/gsh.png" alt="Logo" class="logo-image">
            </div>
            <div class="login-form-container">
                <h2>Giriş Yap</h2>
                <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                <form id="login-form" method="post">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating position-relative">
                        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        <i class="fa fa-eye toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                    <div class="forgot-password">
                        <a href="reset_link.php">Şifremi unuttum</a>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="stayLoggedIn" name="stay_logged_in">
                        <label class="form-check-label" for="stayLoggedIn"> Angemeldet Bleiben</label>
                    </div>
                    <button type="submit" class="login-button btn btn-primary">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/reload.js"></script>
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('floatingPassword');
            const icon = this;

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../api/login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const errorMessage = document.getElementById('error-message');
                if (data.status === 'error') {
                    errorMessage.innerText = data.message;
                    errorMessage.style.display = 'block';
                } else if (data.status === 'success') {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>

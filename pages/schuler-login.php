<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style-v1.0.8.css">
	<!--<script src="../js/reload.js"></script> -->
	 
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
                <form action="login.php" method="post">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Şifremi unuttum</a>
                    </div>
                    <button type="submit" class="login-button btn btn-primary">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
	<script src="js/reload.js"></script>
</body>
</html>

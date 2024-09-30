<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/reset_link.css"> <!-- CSS dosyasını ekliyoruz -->
</head>
<body id="login-page">
    <div class="container">
        <h2>Şifre Sıfırlama</h2>
        <form id="reset-form" method="post" action="../api/send_reset_link.php">
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>
            <button type="submit" class="btn btn-primary">Şifre Sıfırla</button>
        </form>
    </div>
    <script>
        document.getElementById('reset-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('../api/send_reset_link.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Girdiğiniz email adresi sistemimizde kayıtlıysa size bir sıfırlama linki göndereceğiz.');
            window.location.href = 'user_login.php';
        } else if (data.status === 'error') {
            alert(data.message);
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

    </script>
</body>
</html>

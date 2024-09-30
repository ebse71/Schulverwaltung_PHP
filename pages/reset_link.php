<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort Zurücksetzen</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/reset_link.css"> <!-- CSS-Datei einbinden -->
</head>
<body id="login-page">
    <div class="container">
        <h2>Passwort Zurücksetzen</h2>
        <form id="reset-form" method="post" action="../api/send_reset_link.php">
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                <label for="floatingInput">E-Mail-Adresse</label>
            </div>
            <button type="submit" class="btn btn-primary">Passwort Zurücksetzen</button>
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
            alert('Wenn Ihre E-Mail-Adresse in unserem System registriert ist, senden wir Ihnen einen Link zum Zurücksetzen des Passworts.');
            window.location.href = 'user_login.php';
        } else if (data.status === 'error') {
            alert(data.message);
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }
    })
    .catch(error => {
        console.error('Fehler:', error);
    });
});

    </script>
</body>
</html>

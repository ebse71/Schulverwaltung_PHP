<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Rolü Seçimi</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style-v1.0.8.css">
	
</head>
<body id="role-selection-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3 col-sm-6">
                <div class="role-option" onclick="window.location.href='pages/admin.php'">
                    <div class="role-icon">
                        <img src="images/admin-icon.png" alt="Admin" class="img-fluid">
                    </div>
                    <h3>Admin</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="role-option" onclick="window.location.href='pages/lehrer-login.php'">
                    <div class="role-icon">
                        <img src="images/teacher-icon.png" alt="Öğretmen" class="img-fluid">
                    </div>
                    <h3>Öğretmen</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="role-option" onclick="window.location.href='pages/eltern-login.php'">
                    <div class="role-icon">
                        <img src="images/parent-icon.png" alt="Veli" class="img-fluid">
                    </div>
                    <h3>Veli</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="role-option" onclick="window.location.href='pages/schuler-login.php'">
                    <div class="role-icon">
                        <img src="images/student-icon.png" alt="Öğrenci" class="img-fluid">
                    </div>
                    <h3>Öğrenci</h3>
                </div>
            </div>
        </div>
    </div>
	<!--<script src="../js/reload.js"></script> -->
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

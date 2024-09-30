<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/content.css">
	<link rel="stylesheet" href="../css/style_web.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="top-header">
        <img src="../images/school-logo.png" alt="Okul Logosu" class="school-logo">
        <div class="user-info">Hoşgeldin, Frau Baumeister und Herr Baumeister</div>

    </div>
    <div class="wrapper">
        <div class="main-container">
			 		<button id="menu-button">&#9776; Menu</button>
            <!-- Sidebar -->
            <div class="sidebar">
				
                <ul class="menu">
                    <li class="item"><a href="#" data-content="ogretmen-islemleri"><i class="fas fa-user" style="color: #007bff;"></i><span>Öğretmen İşlemleri</span></a></li>
                    <li class="item"><a href="#" data-content="ogrenci-islemleri"><i class="fas fa-user-graduate" style="color: #28a745;"></i><span>Öğrenci İşlemleri</span></a></li>
                    <li class="item"><a href="#" data-content="ders-islemleri"><i class="fas fa-book" style="color: #ffc107;"></i><span>Ders İşlemleri</span></a></li>
                    <li class="item"><a href="#" data-content="ders-programi-islemleri"><i class="fas fa-calendar-alt" style="color: #17a2b8;"></i><span>Ders Programı İşlemleri</span></a></li>
                    <li class="item"><a href="#" data-content="sinif-islemleri"><i class="fas fa-door-open" style="color: #6c757d;"></i><span>Sınıf İşlemleri</span></a></li>
                    <li class="item"><a href="#" data-content="veli-islemleri"><i class="fas fa-user-friends" style="color: #fd7e14;"></i><span>Veli İşlemleri</span></a></li>
                    <li class="item"><a href="#" data-content="duyurular"><i class="fas fa-bullhorn" style="color: #dc3545;"></i><span>Duyurular</span></a></li>
                    <li class="item"><a href="#" data-content="yetkilendirme"><i class="fas fa-user-shield" style="color: #6610f2;"></i><span>Yetkilendirme</span></a></li>
                </ul>
            </div>
            
            <!-- İçerik Alanı -->
            <div class="content" id="content-area">
                <p>Lütfen bir menü seçin.</p>
            </div>
        </div>
    </div>

     <script src="../js/menu.js"></script>
</body>
</html>

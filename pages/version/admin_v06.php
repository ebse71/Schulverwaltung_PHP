<?php
require '../pages/functions.php';

session_start();

if (!checkToken()) {
    // Wenn der Token ungültig ist, nur eine Warnmeldung anzeigen
    echo "<div id='content'><p>Aus Sicherheitsgründen wurde Ihre Sitzung beendet. Bitte melden Sie sich erneut an.</p></div>";
    echo "<script>setTimeout(function() { window.location.href = '../pages/user_login.php'; }, 700);</script>";
    exit();
}

// Wenn der Benutzer die Seite benutzt, aktualisiere die letzte Aktivitätszeit
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Panel</title>
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/content.css">
    <link rel="stylesheet" href="../css/style_web.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="top-header">
        <img src="../images/school-logo.png" alt="Schullogo" class="school-logo">
        <div class="user-info">Willkommen, Frau Baumeister und Herr Baumeister</div>
    </div>
    <div class="wrapper">
        <div class="main-container">
            <button id="menu-button">Menü</button>
            <!-- Seitenleiste -->
            <div class="sidebar">
                <ul class="menu">
                    <li class="item"><a href="#" data-content="lehrer-aktionen"><i class="fas fa-user" style="color: #007bff;"></i><span>Lehreraktionen</span></a></li>
                    <li class="item"><a href="#" data-content="schueler-aktionen"><i class="fas fa-user-graduate" style="color: #28a745;"></i><span>Schüleraktionen</span></a></li>
                    <li class="item"><a href="#" data-content="kurs-aktionen"><i class="fas fa-book" style="color: #ffc107;"></i><span>Kursaktionen</span></a></li>
                    <li class="item"><a href="#" data-content="stundenplan-aktionen"><i class="fas fa-calendar-alt" style="color: #17a2b8;"></i><span>Stundenplanaktionen</span></a></li>
                    <li class="item"><a href="#" data-content="klassen-aktionen"><i class="fas fa-door-open" style="color: #6c757d;"></i><span>Klassenaktionen</span></a></li>
                    <li class="item"><a href="#" data-content="eltern-aktionen"><i class="fas fa-user-friends" style="color: #fd7e14;"></i><span>Elternaktionen</span></a></li>
                    <li class="item"><a href="#" data-content="ankuendigungen"><i class="fas fa-bullhorn" style="color: #dc3545;"></i><span>Ankündigungen</span></a></li>
                    <li class="item"><a href="#" data-content="autorisierung"><i class="fas fa-user-shield" style="color: #6610f2;"></i><span>Autorisierung</span></a></li>
                </ul>
            </div>
            
            <!-- Inhaltsbereich -->
            <div class="content" id="content-area">
                <p>Bitte wählen Sie ein Menü aus.</p>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.sidebar ul.menu li a').forEach(function(menuItem) {
            menuItem.addEventListener('click', function(e) {
                e.preventDefault();
                const contentId = this.getAttribute('data-content');
                loadSubMenu(contentId);
            });
        });

        function loadSubMenu(contentId) {
            const contentArea = document.getElementById('content-area');
            let subMenuHtml = '';

            switch (contentId) {
                case 'lehrer-aktionen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="lehrer-hinzufuegen"><i class="fas fa-user-plus"></i>Lehrer hinzufügen</a>
                            <a href="#" data-action="lehrer-aktualisieren"><i class="fas fa-user-edit"></i>Lehrer aktualisieren</a>
                            <a href="#" data-action="lehrer-loeschen"><i class="fas fa-user-minus"></i>Lehrer löschen</a>
                            <a href="#" data-action="lehrer-liste"><i class="fas fa-list"></i>Lehrerliste</a>
                            <a href="#" data-action="lehrer-unterricht-zuweisen"><i class="fas fa-chalkboard-teacher"></i>Lehrer zuweisen</a>
                        </div>
                    `;
                    break;
                case 'schueler-aktionen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="schueler-hinzufuegen"><i class="fas fa-user-plus"></i>Schüler hinzufügen</a>
                            <a href="#" data-action="schueler-aktualisieren"><i class="fas fa-user-edit"></i>Schüler aktualisieren</a>
                            <a href="#" data-action="schueler-loeschen"><i class="fas fa-user-minus"></i>Schüler löschen</a>
                            <a href="#" data-action="schueler-liste"><i class="fas fa-list"></i>Schülerliste</a>
                            <a href="#" data-action="schueler-noten-bearbeiten"><i class="fas fa-book"></i>Notenverwaltung</a>
                            <a href="#" data-action="schueler-anwesenheit"><i class="fas fa-calendar-check"></i>Anwesenheitsverwaltung</a>
                        </div>
                    `;
                    break;
                case 'kurs-aktionen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="kurs-hinzufuegen"><i class="fas fa-book-open"></i>Kurs hinzufügen</a>
                            <a href="#" data-action="kurs-aktualisieren"><i class="fas fa-edit"></i>Kurs aktualisieren</a>
                            <a href="#" data-action="kurs-loeschen"><i class="fas fa-trash-alt"></i>Kurs löschen</a>
                            <a href="#" data-action="kurs-liste"><i class="fas fa-list"></i>Kursliste</a>
                        </div>
                    `;
                    break;
                case 'stundenplan-aktionen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="stundenplan-hinzufuegen"><i class="fas fa-calendar-plus"></i>Stundenplan hinzufügen</a>
                            <a href="#" data-action="stundenplan-aktualisieren"><i class="fas fa-calendar-edit"></i>Stundenplan aktualisieren</a>
                            <a href="#" data-action="stundenplan-loeschen"><i class="fas fa-calendar-minus"></i>Stundenplan löschen</a>
                            <a href="#" data-action="stundenplan-liste"><i class="fas fa-calendar"></i>Stundenplanliste</a>
                            <a href="#" data-action="stundenplan-konfliktpruefung"><i class="fas fa-exclamation-triangle"></i>Stundenplankonflikt überprüfen</a>
                        </div>
                    `;
                    break;
                case 'klassen-aktionen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="klasse-hinzufuegen"><i class="fas fa-door-open"></i>Klasse hinzufügen</a>
                            <a href="#" data-action="klasse-aktualisieren"><i class="fas fa-edit"></i>Klasse aktualisieren</a>
                            <a href="#" data-action="klasse-loeschen"><i class="fas fa-door-closed"></i>Klasse löschen</a>
                            <a href="#" data-action="klassenlehrer-zuweisen"><i class="fas fa-user-tie"></i>Klassenlehrer zuweisen</a>
                        </div>
                    `;
                    break;
                case 'eltern-aktionen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="elternteil-hinzufuegen"><i class="fas fa-user-friends"></i>Elternteil hinzufügen</a>
                            <a href="#" data-action="elternteil-aktualisieren"><i class="fas fa-user-friends"></i>Elternteil aktualisieren</a>
                            <a href="#" data-action="elternteil-loeschen"><i class="fas fa-user-friends"></i>Elternteil löschen</a>
                        </div>
                    `;
                    break;
                case 'ankuendigungen':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="ankuendigung-hinzufuegen"><i class="fas fa-bullhorn"></i>Ankündigung hinzufügen</a>
                            <a href="#" data-action="allgemeine-ankuendigungen"><i class="fas fa-bullhorn"></i>Allgemeine Ankündigungen</a>
                            <a href="#" data-action="personal-ankuendigungen"><i class="fas fa-bullhorn"></i>Personalankündigungen</a>
                            <a href="#" data-action="ankuendigung-aktualisieren"><i class="fas fa-edit"></i>Ankündigung aktualisieren</a>
                            <a href="#" data-action="ankuendigung-loeschen"><i class="fas fa-trash-alt"></i>Ankündigung löschen</a>
                        </div>
                    `;
                    break;
                case 'autorisierung':
                    subMenuHtml = `
                        <div class="button-grid">
                            <a href="#" data-action="rollenbasierte-autorisierung"><i class="fas fa-user-shield"></i>Rollenbasierte Autorisierung</a>
                            <a href="#" data-action="benutzerbasierte-autorisierung"><i class="fas fa-user-shield"></i>Benutzerbasierte Autorisierung</a>
                        </div>
                    `;
                    break;
                default:
                    subMenuHtml = '<p>Noch kein Inhalt verfügbar.</p>';
                    break;
            }

            reverseAnimateContent(() => {
                contentArea.innerHTML = subMenuHtml;
                animateContent(() => {
                    setupSubButtonListeners();
                });
            });
        }

        function setupSubButtonListeners() {
            const contentArea = document.getElementById('content-area');
            const subButtons = contentArea.querySelectorAll('.button-grid a');

            subButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.getAttribute('data-action');
                    loadContentByAction(action);
                });
            });
        }

        function loadContentByAction(action) {
            const contentArea = document.getElementById('content-area');
            let contentUrl = '';

            switch (action) {
                case 'lehrer-hinzufuegen':
                    contentUrl = '../pages/form_add_teacher.php';
                    break;
                case 'lehrer-loeschen':
                    contentUrl = '../pages/form_delete_teacher.php';
                    break;
                case 'lehrer-aktualisieren':
                    contentUrl = '../pages/form_update_teacher.php';
                    break;
                case 'lehrer-liste':
                    contentUrl = '../pages/form_list_teacher.php';
                    break;
                case 'lehrer-unterricht-zuweisen':
                    contentUrl = '../pages/form_assign_teacher.php';
                    break;
                case 'schueler-hinzufuegen':
                    contentUrl = '../pages/form_add_student.php';
                    break;
                case 'schueler-loeschen':
                    contentUrl = '../pages/form_delete_student.php';
                    break;
                case 'schueler-aktualisieren':
                    contentUrl = '../pages/form_update_student.php';
                    break;
                case 'schueler-liste':
                    contentUrl = '../pages/form_list_student.php';
                    break;
                case 'schueler-noten-bearbeiten':
                    contentUrl = '../pages/form_manage_grades.php';
                    break;
                case 'schueler-anwesenheit':
                    contentUrl = '../pages/form_attendance_student.php';
                    break;
                case 'kurs-hinzufuegen':
                    contentUrl = '../pages/form_add_course.php';
                    break;
                case 'kurs-loeschen':
                    contentUrl = '../pages/form_delete_course.php';
                    break;
                case 'kurs-aktualisieren':
                    contentUrl = '../pages/form_update_course.php';
                    break;
                case 'kurs-liste':
                    contentUrl = '../pages/form_list_courses.php';
                    break;
                case 'stundenplan-hinzufuegen':
                    contentUrl = '../pages/form_add_schedule.php';
                    break;
                case 'stundenplan-loeschen':
                    contentUrl = '../pages/form_delete_schedule.php';
                    break;
                case 'stundenplan-aktualisieren':
                    contentUrl = '../pages/form_update_schedule.php';
                    break;
                case 'stundenplan-liste':
                    contentUrl = '../pages/form_list_schedules.php';
                    break;
                case 'stundenplan-konfliktpruefung':
                    contentUrl = '../pages/form_check_schedule_conflict.php';
                    break;
                case 'klasse-hinzufuegen':
                    contentUrl = '../pages/form_add_class.php';
                    break;
                case 'klasse-loeschen':
                    contentUrl = '../pages/form_delete_class.php';
                    break;
                case 'klasse-aktualisieren':
                    contentUrl = '../pages/form_update_class.php';
                    break;
                case 'klassenlehrer-zuweisen':
                    contentUrl = '../pages/form_assign_class_teacher.php';
                    break;
                case 'elternteil-hinzufuegen':
                    contentUrl = '../pages/form_add_parent.php';
                    break;
                case 'elternteil-loeschen':
                    contentUrl = '../pages/form_delete_parent.php';
                    break;
                case 'elternteil-aktualisieren':
                    contentUrl = '../pages/form_update_parent.php';
                    break;
                case 'ankuendigung-hinzufuegen':
                    contentUrl = '../pages/form_add_announcement.php';
                    break;
                case 'allgemeine-ankuendigungen':
                    contentUrl = '../pages/form_list_announcements.php';
                    break;
                case 'personal-ankuendigungen':
                    contentUrl = '../pages/form_list_staff_announcements.php';
                    break;
                case 'ankuendigung-loeschen':
                    contentUrl = '../pages/form_delete_announcement.php';
                    break;
                case 'ankuendigung-aktualisieren':
                    contentUrl = '../pages/form_update_announcement.php';
                    break;
                case 'rollenbasierte-autorisierung':
                    contentUrl = '../pages/form_role_based_auth.php';
                    break;
                case 'benutzerbasierte-autorisierung':
                    contentUrl = '../pages/form_user_based_auth.php';
                    break;
                default:
                    contentArea.innerHTML = '<p>Noch kein Inhalt verfügbar.</p>';
                    return;
            }

            fetch(contentUrl)
                .then(response => response.text())
                .then(html => {
                    contentArea.innerHTML = html;
                    setupFormSubmitListener();
                })
                .catch(error => {
                    contentArea.innerHTML = `<p>Fehler beim Laden des Inhalts: ${error.message}</p>`;
                });
        }

        function setupFormSubmitListener() {
            const teacherForm = document.querySelector('.teacher-form');

            if (teacherForm) {
                teacherForm.removeEventListener('submit', formSubmitHandler);
                teacherForm.addEventListener('submit', formSubmitHandler);
            }
        }

        function formSubmitHandler(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch('form_add_teacher.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                document.getElementById('content-area').innerHTML = result;
                setupFormSubmitListener();
            })
            .catch(error => {
                console.error('Fehler:', error);
            });
        }

        function animateContent(callback) {
            const items = document.querySelectorAll('.button-grid a');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('show');
                    if (index === items.length - 1 && typeof callback === 'function') {
                        setTimeout(callback, 100);
                    }
                }, index * 100);
            });
        }

        function reverseAnimateContent(callback) {
            const items = document.querySelectorAll('.button-grid a.show');
            const totalItems = items.length;

            if (totalItems === 0) {
                if (typeof callback === 'function') {
                    callback();
                }
                return;
            }

            const delay = 100;

            for (let i = totalItems - 1; i >= 0; i--) {
                setTimeout(() => {
                    items[i].classList.remove('show');
                    items[i].style.pointerEvents = 'none';

                    if (i === 0 && typeof callback === 'function') {
                        setTimeout(callback, delay);
                    }
                }, (totalItems - 1 - i) * delay);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('menu-button');
            const sidebar = document.querySelector('.sidebar');

            if (menuButton && sidebar) {
                menuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('open');
                });

                document.addEventListener('click', function(e) {
                    if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                        sidebar.classList.remove('open');
                    }
                });
            }

            document.querySelectorAll('.sidebar ul.menu li a').forEach(function(menuItem) {
                menuItem.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                });
            });
        });
    </script>
</body>
</html>

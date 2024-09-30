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
        case 'lehrer-verwaltung':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="lehrer-hinzufuegen"><i class="fas fa-user-plus"></i>Lehrer Hinzufügen</a>
                    <a href="#" data-action="lehrer-aktualisieren"><i class="fas fa-user-edit"></i>Lehrer Aktualisieren</a>
                    <a href="#" data-action="lehrer-loeschen"><i class="fas fa-user-minus"></i>Lehrer Löschen</a>
                    <a href="#" data-action="lehrer-liste"><i class="fas fa-list"></i>Lehrerliste</a>
                    <a href="#" data-action="lehrer-unterricht-zuweisen"><i class="fas fa-chalkboard-teacher"></i>Lehrer Unterricht Zuweisen</a>
                </div>
            `;
            break;
        case 'schueler-verwaltung':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="schueler-hinzufuegen"><i class="fas fa-user-plus"></i>Schüler Hinzufügen</a>
                    <a href="#" data-action="schueler-aktualisieren"><i class="fas fa-user-edit"></i>Schüler Aktualisieren</a>
                    <a href="#" data-action="schueler-loeschen"><i class="fas fa-user-minus"></i>Schüler Löschen</a>
                    <a href="#" data-action="schueler-liste"><i class="fas fa-list"></i>Schülerliste</a>
                    <a href="#" data-action="schueler-noten-bearbeiten"><i class="fas fa-book"></i>Schülernoten Bearbeiten</a>
                    <a href="#" data-action="schueler-anwesenheit"><i class="fas fa-calendar-check"></i>Schüler Anwesenheit</a>
                </div>
            `;
            break;
        case 'unterricht-verwaltung':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="unterricht-hinzufuegen"><i class="fas fa-book-open"></i>Unterricht Hinzufügen</a>
                    <a href="#" data-action="unterricht-aktualisieren"><i class="fas fa-edit"></i>Unterricht Aktualisieren</a>
                    <a href="#" data-action="unterricht-loeschen"><i class="fas fa-trash-alt"></i>Unterricht Löschen</a>
                    <a href="#" data-action="unterricht-liste"><i class="fas fa-list"></i>Unterrichtsliste</a>
                </div>
            `;
            break;
        case 'stundenplan-verwaltung':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="stundenplan-hinzufuegen"><i class="fas fa-calendar-plus"></i>Stundenplan Hinzufügen</a>
                    <a href="#" data-action="stundenplan-aktualisieren"><i class="fas fa-calendar-edit"></i>Stundenplan Aktualisieren</a>
                    <a href="#" data-action="stundenplan-loeschen"><i class="fas fa-calendar-minus"></i>Stundenplan Löschen</a>
                    <a href="#" data-action="stundenplan-liste"><i class="fas fa-calendar"></i>Stundenplan Liste</a>
                    <a href="#" data-action="stundenplan-konfliktpruefung"><i class="fas fa-exclamation-triangle"></i>Stundenplan Konfliktprüfung</a>
                </div>
            `;
            break;
        case 'klassen-verwaltung':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="klasse-hinzufuegen"><i class="fas fa-door-open"></i>Klasse Hinzufügen</a>
                    <a href="#" data-action="klasse-aktualisieren"><i class="fas fa-edit"></i>Klasse Aktualisieren</a>
                    <a href="#" data-action="klasse-loeschen"><i class="fas fa-door-closed"></i>Klasse Löschen</a>
                    <a href="#" data-action="klassenlehrer-zuweisen"><i class="fas fa-user-tie"></i>Klassenlehrer Zuweisen</a>
                </div>
            `;
            break;
        case 'eltern-verwaltung':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="eltern-hinzufuegen"><i class="fas fa-user-friends"></i>Elternteil Hinzufügen</a>
                    <a href="#" data-action="eltern-aktualisieren"><i class="fas fa-user-friends"></i>Elternteil Aktualisieren</a>
                    <a href="#" data-action="eltern-loeschen"><i class="fas fa-user-friends"></i>Elternteil Löschen</a>
                </div>
            `;
            break;
        case 'mitteilungen':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="mitteilung-hinzufuegen"><i class="fas fa-bullhorn"></i>Mitteilung Hinzufügen</a>
                    <a href="#" data-action="allgemeine-mitteilungen"><i class="fas fa-bullhorn"></i>Allgemeine Mitteilungen</a>
                    <a href="#" data-action="personelle-mitteilungen"><i class="fas fa-bullhorn"></i>Personelle Mitteilungen</a>
                    <a href="#" data-action="mitteilung-aktualisieren"><i class="fas fa-edit"></i>Mitteilung Aktualisieren</a>
                    <a href="#" data-action="mitteilung-loeschen"><i class="fas fa-trash-alt"></i>Mitteilung Löschen</a>
                </div>
            `;
            break;
        case 'berechtigungen':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="rollenbasierte-berechtigungen"><i class="fas fa-user-shield"></i>Rollenbasierte Berechtigungen</a>
                    <a href="#" data-action="benutzerbasierte-berechtigungen"><i class="fas fa-user-shield"></i>Benutzerbasierte Berechtigungen</a>
                </div>
            `;
            break;
        default:
            subMenuHtml = '<p>Inhalt ist derzeit nicht verfügbar.</p>';
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
        case 'unterricht-hinzufuegen':
            contentUrl = '../pages/form_add_course.php';
            break;
        case 'unterricht-loeschen':
            contentUrl = '../pages/form_delete_course.php';
            break;
        case 'unterricht-aktualisieren':
            contentUrl = '../pages/form_update_course.php';
            break;
        case 'unterricht-liste':
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
        case 'eltern-hinzufuegen':
            contentUrl = '../pages/form_add_parent.php';
            break;
        case 'eltern-loeschen':
            contentUrl = '../pages/form_delete_parent.php';
            break;
        case 'eltern-aktualisieren':
            contentUrl = '../pages/form_update_parent.php';
            break;
        case 'mitteilung-hinzufuegen':
            contentUrl = '../pages/form_add_announcement.php';
            break;
        case 'allgemeine-mitteilungen':
            contentUrl = '../pages/form_list_announcements.php';
            break;
        case 'personelle-mitteilungen':
            contentUrl = '../pages/form_list_staff_announcements.php';
            break;
        case 'mitteilung-loeschen':
            contentUrl = '../pages/form_delete_announcement.php';
            break;
        case 'mitteilung-aktualisieren':
            contentUrl = '../pages/form_update_announcement.php';
            break;
        case 'rollenbasierte-berechtigungen':
            contentUrl = '../pages/form_role_based_auth.php';
            break;
        case 'benutzerbasierte-berechtigungen':
            contentUrl = '../pages/form_user_based_auth.php';
            break;
        default:
            contentArea.innerHTML = '<p>Inhalt ist derzeit nicht verfügbar.</p>';
            return;
    }

    if (contentUrl) {
        fetch(contentUrl)
            .then(response => response.text())
            .then(html => {
                contentArea.innerHTML = html;
                setupFormSubmitListener(); // Listener nach dem Laden des Formulars erneut setzen
            })
            .catch(error => {
                contentArea.innerHTML = `<p>Fehler beim Laden des Inhalts: ${error.message}</p>`;
            });
    }
}

function setupFormSubmitListener() {
    const teacherForm = document.querySelector('.teacher-form');

    if (teacherForm) {
        teacherForm.removeEventListener('submit', formSubmitHandler);
        teacherForm.addEventListener('submit', formSubmitHandler);
    }
}

function formSubmitHandler(e) {
    e.preventDefault(); // Standard-Formularsendung verhindern

    let formData = new FormData(this);

    fetch('form_add_teacher.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById('content-area').innerHTML = result;
        setupFormSubmitListener(); // Listener nach dem Neuladen des Formulars erneut setzen
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
        // Menü-Button Klickereignis zur Anzeige/Verbergung der Sidebar
        menuButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Event-Verbreitung verhindern
            sidebar.classList.toggle('open');
        });

        // Klick auf beliebige Stelle außerhalb der Sidebar, um sie zu verbergen
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // Schließen der Sidebar bei Klick auf ein Menüelement
    document.querySelectorAll('.sidebar ul.menu li a').forEach(function(menuItem) {
        menuItem.addEventListener('click', function() {
            sidebar.classList.remove('open');
        });
    });
});

// Zusätzliche Menü-JS-Code hier...

// Zeitüberschreitungsüberprüfungscode:
let timeout;

function startTimeout() {
    timeout = setTimeout(() => {
        alert("Ihre Sitzung ist abgelaufen. Bitte melden Sie sich erneut an.");
        window.location.href = "../pages/user_login.php";
    }, 5 * 60 * 1000); // 5 Minuten (300.000 ms)
}

function resetTimeout() {
    clearTimeout(timeout);
    startTimeout();
}

// Starten/Zurücksetzen des Timers bei Seitenladevorgang oder Benutzereingaben
window.onload = startTimeout;
document.onmousemove = resetTimeout;
document.onkeydown = resetTimeout;

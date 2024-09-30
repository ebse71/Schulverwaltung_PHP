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
        case 'ogretmen-islemleri':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="ogretmen-ekle"><i class="fas fa-user-plus"></i>Öğretmen Ekle</a>
                    <a href="#" data-action="ogretmen-sil"><i class="fas fa-user-minus"></i>Öğretmen Sil</a>
                    <a href="#" data-action="ogretmen-guncelle"><i class="fas fa-user-edit"></i>Öğretmen Güncelle</a>
                    <a href="#" data-action="ogretmen-listesi"><i class="fas fa-list"></i>Öğretmen Listesi</a>
                    <a href="#" data-action="ogretmen-ders-atama"><i class="fas fa-chalkboard-teacher"></i>Öğretmen Ders Atama</a>
                </div>
            `;
            break;
        case 'ogrenci-islemleri':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="ogrenci-ekle"><i class="fas fa-user-plus"></i>Öğrenci Ekle</a>
                    <a href="#" data-action="ogrenci-sil"><i class="fas fa-user-minus"></i>Öğrenci Sil</a>
                    <a href="#" data-action="ogrenci-guncelle"><i class="fas fa-user-edit"></i>Öğrenci Güncelle</a>
                    <a href="#" data-action="ogrenci-listesi"><i class="fas fa-list"></i>Öğrenci Listesi</a>
                    <a href="#" data-action="ogrenci-not-islemleri"><i class="fas fa-book"></i>Öğrenci Not İşlemleri</a>
                    <a href="#" data-action="ogrenci-devamsizlik"><i class="fas fa-calendar-check"></i>Öğrenci Devamsızlık</a>
                </div>
            `;
            break;
        case 'ders-islemleri':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="ders-ekle"><i class="fas fa-book-open"></i>Ders Ekle</a>
                    <a href="#" data-action="ders-sil"><i class="fas fa-trash-alt"></i>Ders Sil</a>
                    <a href="#" data-action="ders-guncelle"><i class="fas fa-edit"></i>Ders Güncelle</a>
                    <a href="#" data-action="ders-listesi"><i class="fas fa-list"></i>Ders Listesi</a>
                </div>
            `;
            break;
        case 'ders-programi-islemleri':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="ders-programi-ekle"><i class="fas fa-calendar-plus"></i>Ders Programı Ekle</a>
                    <a href="#" data-action="ders-programi-sil"><i class="fas fa-calendar-minus"></i>Ders Programı Sil</a>
                    <a href="#" data-action="ders-programi-guncelle"><i class="fas fa-calendar-edit"></i>Ders Programı Güncelle</a>
                    <a href="#" data-action="ders-programi-listesi"><i class="fas fa-calendar"></i>Ders Programı Listesi</a>
                    <a href="#" data-action="ders-programi-cakis-kontrolu"><i class="fas fa-exclamation-triangle"></i>Ders Programı Çakışma Kontrolü</a>
                </div>
            `;
            break;
        case 'sinif-islemleri':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="sinif-ekle"><i class="fas fa-door-open"></i>Sınıf Ekle</a>
                    <a href="#" data-action="sinif-sil"><i class="fas fa-door-closed"></i>Sınıf Sil</a>
                    <a href="#" data-action="sinif-guncelle"><i class="fas fa-edit"></i>Sınıf Güncelle</a>
                    <a href="#" data-action="sinif-ogretmeni-atama"><i class="fas fa-user-tie"></i>Sınıf Öğretmeni Atama</a>
                </div>
            `;
            break;
        case 'veli-islemleri':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="veli-ekle">
                        <div class="veli-icon-wrapper">
                            <i class="fas fa-user-friends"></i>
                            <i class="fas fa-plus small-icon"></i>
                        </div>
                        Veli Ekle
                    </a>
                    <a href="#" data-action="veli-sil">
                        <div class="veli-icon-wrapper">
                            <i class="fas fa-user-friends"></i>
                            <i class="fas fa-minus small-icon"></i>
                        </div>
                        Veli Sil
                    </a>
                    <a href="#" data-action="veli-guncelle">
                        <div class="veli-icon-wrapper">
                            <i class="fas fa-user-friends"></i>
                            <i class="fas fa-edit small-icon"></i>
                        </div>
                        Veli Güncelle
                    </a>
                </div>
            `;
            break;
        case 'duyurular':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="duyuru-ekle"><i class="fas fa-bullhorn"></i>Duyuru Ekle</a>
                    <a href="#" data-action="genel-duyurular"><i class="fas fa-bullhorn"></i>Genel Duyurular</a>
                    <a href="#" data-action="personel-duyurulari"><i class="fas fa-bullhorn"></i>Personel Duyuruları</a>
                    <a href="#" data-action="duyuru-sil"><i class="fas fa-trash-alt"></i>Duyuru Sil</a>
                    <a href="#" data-action="duyuru-guncelle"><i class="fas fa-edit"></i>Duyuru Güncelle</a>
                </div>
            `;
            break;
        case 'yetkilendirme':
            subMenuHtml = `
                <div class="button-grid">
                    <a href="#" data-action="rol-bazli-yetkilendirme"><i class="fas fa-user-shield"></i>Rol Bazlı Yetkilendirme</a>
                    <a href="#" data-action="kullanici-bazli-yetkilendirme"><i class="fas fa-user-shield"></i>Kullanıcı Bazlı Yetkilendirme</a>
                </div>
            `;
            break;
        default:
            subMenuHtml = '<p>Henüz içerik mevcut değil.</p>';
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
            handleSubButtonClick(action);
        });
    });
}

function handleSubButtonClick(action) {
    reverseAnimateContent(() => {
        loadContentByAction(action);
    });
}

function loadContentByAction(action) {
    const contentArea = document.getElementById('content-area');
    let contentUrl = '';

    switch (action) {
        case 'ogretmen-ekle':
            contentUrl = '../pages/form_add_teacher.php';
            break;
        case 'ogretmen-sil':
            contentUrl = '../pages/form_delete_teacher.php';
            break;
        case 'ogretmen-guncelle':
            contentUrl = '../pages/form_update_teacher.php';
            break;
        case 'ogretmen-listesi':
            contentUrl = '../pages/form_list_teacher.php';
            break;
        case 'ogretmen-ders-atama':
            contentUrl = '../pages/form_assign_teacher.php';
            break;
        case 'ogrenci-ekle':
            contentUrl = '../pages/form_add_student.php';
            break;
        case 'ogrenci-sil':
            contentUrl = '../pages/form_delete_student.php';
            break;
        case 'ogrenci-guncelle':
            contentUrl = '../pages/form_update_student.php';
            break;
        case 'ogrenci-listesi':
            contentUrl = '../pages/form_list_student.php';
            break;
        case 'ogrenci-not-islemleri':
            contentUrl = '../pages/form_manage_grades.php';
            break;
        case 'ogrenci-devamsizlik':
            contentUrl = '../pages/form_attendance_student.php';
            break;
        case 'ders-ekle':
            contentUrl = '../pages/form_add_course.php';
            break;
        case 'ders-sil':
            contentUrl = '../pages/form_delete_course.php';
            break;
        case 'ders-guncelle':
            contentUrl = '../pages/form_update_course.php';
            break;
        case 'ders-listesi':
            contentUrl = '../pages/form_list_courses.php';
            break;
        case 'ders-programi-ekle':
            contentUrl = '../pages/form_add_schedule.php';
            break;
        case 'ders-programi-sil':
            contentUrl = '../pages/form_delete_schedule.php';
            break;
        case 'ders-programi-guncelle':
            contentUrl = '../pages/form_update_schedule.php';
            break;
        case 'ders-programi-listesi':
            contentUrl = '../pages/form_list_schedules.php';
            break;
        case 'ders-programi-cakis-kontrolu':
            contentUrl = '../pages/form_check_schedule_conflict.php';
            break;
        case 'sinif-ekle':
            contentUrl = '../pages/form_add_class.php';
            break;
        case 'sinif-sil':
            contentUrl = '../pages/form_delete_class.php';
            break;
        case 'sinif-guncelle':
            contentUrl = '../pages/form_update_class.php';
            break;
        case 'sinif-ogretmeni-atama':
            contentUrl = '../pages/form_assign_class_teacher.php';
            break;
        case 'veli-ekle':
            contentUrl = '../pages/form_add_parent.php';
            break;
        case 'veli-sil':
            contentUrl = '../pages/form_delete_parent.php';
            break;
        case 'veli-guncelle':
            contentUrl = '../pages/form_update_parent.php';
            break;
        case 'duyuru-ekle':
            contentUrl = '../pages/form_add_announcement.php';
            break;
        case 'genel-duyurular':
            contentUrl = '../pages/form_list_announcements.php';
            break;
        case 'personel-duyurulari':
            contentUrl = '../pages/form_list_staff_announcements.php';
            break;
        case 'duyuru-sil':
            contentUrl = '../pages/form_delete_announcement.php';
            break;
        case 'duyuru-guncelle':
            contentUrl = '../pages/form_update_announcement.php';
            break;
        case 'rol-bazli-yetkilendirme':
            contentUrl = '../pages/form_role_based_auth.php';
            break;
        case 'kullanici-bazli-yetkilendirme':
            contentUrl = '../pages/form_user_based_auth.php';
            break;
        default:
            contentArea.innerHTML = '<p>Henüz içerik mevcut değil.</p>';
            return;
    }

    fetch(contentUrl)
        .then(response => response.text())
        .then(html => {
            contentArea.innerHTML = html;
            setupFormSubmitListener();
        })
        .catch(error => {
            contentArea.innerHTML = `<p>İçerik yüklenirken bir hata oluştu: ${error.message}</p>`;
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
    e.preventDefault(); // Formun varsayılan gönderimini engelle

    let formData = new FormData(this);

    fetch('form_add_teacher.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById('content-area').innerHTML = result;
        setupFormSubmitListener(); // Form yüklendikten sonra tekrar listener ekle
    })
    .catch(error => {
        console.error('Hata:', error);
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
    // Sayfa yüklendiğinde yapılacak işlemler
});

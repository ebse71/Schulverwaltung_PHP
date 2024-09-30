document.addEventListener("DOMContentLoaded", function() {
    var sidebarToggle = document.querySelector('.content-toggle');
    var sidebar = document.querySelector('.sidebar');

    // Sidebar menü butonuna tıklayınca sidebar'ı göster/gizle
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });

    var menuItems = document.querySelectorAll('.menu .btn');

    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            var submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains('submenu')) {
                submenu.style.maxHeight = submenu.style.maxHeight ? null : submenu.scrollHeight + "px";
            }
        });
    });
});

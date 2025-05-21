import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Highlight active menu item
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll('.sidebar-link');
    
    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
            
            // Expand parent menu if this is a submenu item
            let parentItem = link.closest('.sidebar-submenu');
            if (parentItem) {
                parentItem.style.display = 'block';
                parentItem.previousElementSibling.classList.add('active');
            }
        }
    });
});
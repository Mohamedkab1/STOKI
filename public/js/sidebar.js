/**
 * =================================================================
 *  STOKI ERP — Sidebar Toggle Logic
 *  Fichier : public/js/sidebar.js
 * =================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn = document.getElementById('sidebar-close'); // si un btn X existe dans la sidebar
    
    if (!sidebar || !overlay) {
        return;
    }

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Désactiver le scroll sur la page
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = ''; // Réactiver le scroll
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    // Fermer sidebar auto sur resize vers desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            }
        }
    });
});

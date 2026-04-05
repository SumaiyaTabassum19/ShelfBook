document.addEventListener('DOMContentLoaded', () => {
    // 1. Selectors
    const header = document.querySelector('.main_app_header');
    const primaryNav = document.querySelector('.primary_navbar');
    const userDropdown = document.querySelector('.user_dropdown_panel');
    const navBtn = document.getElementById('user_menu_btn');
    const userBtn = document.getElementById('user_btn');

    // 2. Helper: Close all open menus
    const closeAllMenus = () => {
        [primaryNav, userDropdown].forEach(el => el?.classList.remove('active'));
        [navBtn, userBtn].forEach(btn => btn?.setAttribute('aria-expanded', 'false'));
    };

    // 3. Helper: Toggle specific state
    const toggleMenu = (target, trigger) => {
        const isActive = target.classList.contains('active');
        
        // Close everything first
        closeAllMenus();

        // If it wasn't active, open it now
        if (!isActive) {
            target.classList.add('active');
            trigger?.setAttribute('aria-expanded', 'true');
        }
    };

    // --- Event Listeners ---

    // Toggle Mobile Navigation
    navBtn?.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevents immediate closing by document listener
        if (primaryNav) toggleMenu(primaryNav, navBtn);
    });

    // Toggle User Account Dropdown
    userBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (userDropdown) toggleMenu(userDropdown, userBtn);
    });

    // Close on Outside Click
    document.addEventListener('click', (event) => {
        const isClickInsideMenu = primaryNav?.contains(event.target) || userDropdown?.contains(event.target);
        const isClickOnBtn = navBtn?.contains(event.target) || userBtn?.contains(event.target);

        if (!isClickInsideMenu && !isClickOnBtn) {
            closeAllMenus();
        }
    });

    // --- Scroll Handler (Optimized) ---
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        // 1. Sticky Header Effect
        if (currentScrollY > 70) {
            header?.classList.add('fixed_active');
        } else {
            header?.classList.remove('fixed_active');
        }

        // 2. Auto-close menus on significant scroll (more than 10px)
        if (Math.abs(currentScrollY - lastScrollY) > 10) {
            const isAnyMenuOpen = primaryNav?.classList.contains('active') || userDropdown?.classList.contains('active');
            if (isAnyMenuOpen) closeAllMenus();
        }

        lastScrollY = currentScrollY;
    }, { passive: true }); // Improved scroll performance
});
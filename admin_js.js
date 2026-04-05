// === ADMIN DASHBOARD CONTROLS (V15: Fixed Sidebar Layout) ===

// 1. Sidebar/Menu Toggle (for mobile responsiveness)
let sidebar = document.querySelector('.sidebar_v15');
// Since the sidebar is fixed, we need a separate menu button. 
// If you don't have one in your main content, you should add one, 
// e.g., <i id="menu_toggle_btn" class="fas fa-bars"></i> inside the main_content_v15 area.
let menuToggleBtn = document.getElementById('menu_btn'); // Assuming you reuse the old admin_header's menu_btn ID

if (menuToggleBtn) {
    menuToggleBtn.onclick = () => {
        // Toggles the 'active' class on the sidebar to slide it in/out on mobile
        sidebar.classList.toggle('active');
        // Close the user box if the menu is opened
        if (adminAccBox) {
            adminAccBox.classList.remove('active');
        }
    };
} else {
    console.warn("Menu toggle button (#menu_btn) not found. Sidebar will not toggle on mobile.");
}

// 2. User Account Dropdown (if you transition back to a dropdown style from the header)
// Note: In the V15 design, user info is always visible in the sidebar, 
// but if you want an account dropdown in the main area (like the old V13 header), 
// you'll need elements for it. This assumes you are NOT using the user_header.php
// which already has the account box logic.

// --- If you want to use the V13 Header (Minimalist) with this V15 dashboard: ---
// -----------------------------------------------------------------------------
let adminAccBox = document.querySelector('.admin_acc_box_v13');
let userBtn = document.getElementById('user_btn');

if (userBtn && adminAccBox) {
    userBtn.onclick = () => {
        // Toggle the 'active' class on the account dropdown box
        adminAccBox.classList.toggle('active');
        // Close the sidebar if the user box is opened
        if (sidebar) {
            sidebar.classList.remove('active');
        }
    };

    // Close menus when the user clicks anywhere outside (useful for dropdowns)
    window.addEventListener('click', function(e) {
        // Check if the click target is neither the user button nor inside the account box
        if (!e.target.closest('.admin_acc_box_v13') && e.target !== userBtn) {
            adminAccBox.classList.remove('active');
        }
        // Check if the click target is neither the menu button nor inside the sidebar
        if (!e.target.closest('.sidebar_v15') && e.target !== menuToggleBtn) {
            sidebar.classList.remove('active');
        }
    });
}
// -----------------------------------------------------------------------------


// 3. Simple Active Link Highlighting (Best Practice for Navigations)
document.addEventListener("DOMContentLoaded", function() {
    const navLinks = document.querySelectorAll('.sidebar_nav .nav_item');
    const currentPath = window.location.pathname.split('/').pop();

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href').split('/').pop();

        // Check if the link href matches the current file name
        if (linkPath === currentPath) {
            link.classList.add('active');
        } else {
             link.classList.remove('active');
        }
    });
});
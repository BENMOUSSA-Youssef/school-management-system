/**
 * ============================================
 * ui.js - UI Only Functions (No Data Management)
 * ============================================
 * 
 * This file handles ONLY UI interactions:
 * - Sidebar toggle
 * - Theme switching
 * - Basic UI helpers
 * 
 * NO data storage or CRUD operations here!
 * All data is handled by Laravel backend.
 */

/**
 * Toggle sidebar on mobile
 */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('open');
    }
}

/**
 * Set theme (dark/light)
 */
function setTheme(theme) {
    if (theme === 'dark') {
        document.body.classList.add('dark-theme');
        document.body.classList.remove('light-theme');
        const darkBtn = document.getElementById('darkThemeBtn');
        const lightBtn = document.getElementById('lightThemeBtn');
        if (darkBtn) darkBtn.classList.add('active');
        if (lightBtn) lightBtn.classList.remove('active');
    } else {
        document.body.classList.add('light-theme');
        document.body.classList.remove('dark-theme');
        const darkBtn = document.getElementById('darkThemeBtn');
        const lightBtn = document.getElementById('lightThemeBtn');
        if (darkBtn) darkBtn.classList.remove('active');
        if (lightBtn) lightBtn.classList.add('active');
    }
    localStorage.setItem('theme', theme);
}

/**
 * Initialize theme on page load
 */
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(savedTheme);
}

/**
 * Logout user (redirects to Laravel logout route)
 * Note: This function should be called from Blade templates where the route is available
 */
function logoutUser() {
    if (confirm('Are you sure you want to logout?')) {
        // Create a form to submit POST request to Laravel logout
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout';
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = csrfToken.getAttribute('content');
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize theme when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTheme);
} else {
    initializeTheme();
}


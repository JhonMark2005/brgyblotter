/**
 * Digital Barangay Blotter — Main JavaScript
 */

'use strict';

// -------------------------------------------------------
// Confirm delete dialog
// -------------------------------------------------------
function confirmDelete(label) {
    return window.confirm(
        `Are you sure you want to delete this ${label}?\n\nThis action cannot be undone.`
    );
}

// -------------------------------------------------------
// Auto-dismiss flash messages after 5 seconds
// -------------------------------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const flashes = document.querySelectorAll('[data-flash]');
    flashes.forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 500);
        }, 5000);
    });

    // -------------------------------------------------------
    // Confirm forms with data-confirm attribute
    // -------------------------------------------------------
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const msg = form.getAttribute('data-confirm');
            if (!window.confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // -------------------------------------------------------
    // Print button shortcut
    // -------------------------------------------------------
    document.querySelectorAll('[data-print]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            window.print();
        });
    });
});

// -------------------------------------------------------
// Password visibility toggle helper (used in login)
// -------------------------------------------------------
function togglePassword(inputId) {
    const input = document.getElementById(inputId || 'password');
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
}

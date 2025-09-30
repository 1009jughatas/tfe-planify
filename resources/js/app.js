import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Mobile navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileNav = document.querySelector('[x-data*="mobileMenuOpen"]');
        if (mobileNav && !mobileNav.contains(event.target)) {
            const mobileMenuOpen = mobileNav._x_dataStack[0].mobileMenuOpen;
            if (mobileMenuOpen) {
                mobileNav._x_dataStack[0].mobileMenuOpen = false;
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            // Close mobile menu on desktop
            const mobileNav = document.querySelector('[x-data*="mobileMenuOpen"]');
            if (mobileNav) {
                mobileNav._x_dataStack[0].mobileMenuOpen = false;
            }
        }
    });

    // Prevent zoom on double tap for iOS
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

Alpine.start();

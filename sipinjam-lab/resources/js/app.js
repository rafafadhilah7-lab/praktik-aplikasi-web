
/**
 * SIPINJAM-LAB
 * Modern Dashboard — Navy & Sky Blue
 *
 * Features:
 * 1. Light Mode / Dark Mode
 * 2. Menyimpan preferensi tema
 * 3. Mobile sidebar drawer
 * 4. Menutup sidebar melalui overlay, tombol Escape,
 *    atau setelah memilih menu
 */

(() => {
    'use strict';

    const root = document.documentElement;

    // =========================================
    // THEME MANAGEMENT
    // =========================================

    const themeToggle = document.querySelector('[data-theme-toggle]');
    const lightIcon = document.querySelector('[data-theme-icon="light"]');
    const darkIcon = document.querySelector('[data-theme-icon="dark"]');

    const getSavedTheme = () => {
        try {
            const savedTheme = localStorage.getItem('sipinjam-theme');

            return savedTheme === 'dark' ? 'dark' : 'light';
        } catch (error) {
            return 'light';
        }
    };

    const updateThemeIcon = (theme) => {
        if (lightIcon) {
            lightIcon.style.display = theme === 'dark' ? 'none' : 'block';
        }

        if (darkIcon) {
            darkIcon.style.display = theme === 'dark' ? 'block' : 'none';
        }

        if (themeToggle) {
            themeToggle.setAttribute(
                'aria-label',
                theme === 'dark' ? 'Aktifkan Light Mode' : 'Aktifkan Dark Mode'
            );

            themeToggle.setAttribute(
                'title',
                theme === 'dark' ? 'Aktifkan Light Mode' : 'Aktifkan Dark Mode'
            );

            themeToggle.setAttribute(
                'aria-pressed',
                theme === 'dark' ? 'true' : 'false'
            );
        }
    };

    const applyTheme = (theme) => {
        const selectedTheme = theme === 'dark' ? 'dark' : 'light';

        root.setAttribute('data-theme', selectedTheme);

        updateThemeIcon(selectedTheme);

        try {
            localStorage.setItem('sipinjam-theme', selectedTheme);
        } catch (error) {
            // Tema tetap bisa berubah meskipun penyimpanan tidak tersedia.
        }
    };

    // Terapkan tema saat aplikasi dimuat.
    applyTheme(getSavedTheme());

    // Tombol pergantian tema.
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentTheme = root.getAttribute('data-theme') || 'light';

            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

            applyTheme(nextTheme);
        });
    }

    // =========================================
    // MOBILE SIDEBAR
    // =========================================

    const sidebar = document.getElementById('appSidebar');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    const isMobile = () => window.matchMedia('(max-width: 900px)').matches;

    const openSidebar = () => {
        if (!sidebar || !isMobile()) {
            return;
        }

        sidebar.classList.add('is-open');

        if (sidebarOverlay) {
            sidebarOverlay.classList.add('is-visible');
        }

        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', 'true');
            sidebarToggle.setAttribute('aria-label', 'Tutup navigasi');
        }

        document.body.style.overflow = 'hidden';
    };

    const closeSidebar = () => {
        if (!sidebar) {
            return;
        }

        sidebar.classList.remove('is-open');

        if (sidebarOverlay) {
            sidebarOverlay.classList.remove('is-visible');
        }

        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', 'false');
            sidebarToggle.setAttribute('aria-label', 'Buka navigasi');
        }

        document.body.style.overflow = '';
    };

    // Tombol buka/tutup sidebar.
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            const sidebarIsOpen = sidebar?.classList.contains('is-open');

            if (sidebarIsOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    // Klik area gelap untuk menutup sidebar.
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Tutup sidebar setelah pengguna memilih menu.
    if (sidebar) {
        const sidebarLinks = sidebar.querySelectorAll('a');

        sidebarLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (isMobile()) {
                    closeSidebar();
                }
            });
        });
    }

    // Tombol Escape menutup sidebar.
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // Tutup sidebar jika layar berubah ke desktop.
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeSidebar();
        }
    });

    // =========================================
    // LANDING MOBILE MENU
    // =========================================

    const landingMenuToggle = document.querySelector('[data-landing-menu-toggle]');
    const landingMobileMenu = document.getElementById('landingMobileMenu');

    if (landingMenuToggle && landingMobileMenu) {
        const closeLandingMenu = () => {
            landingMobileMenu.classList.add('hidden');
            landingMenuToggle.setAttribute('aria-expanded', 'false');
        };

        landingMenuToggle.addEventListener('click', () => {
            const isHidden = landingMobileMenu.classList.toggle('hidden');
            landingMenuToggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
        });

        landingMobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeLandingMenu);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeLandingMenu();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                closeLandingMenu();
            }
        });
    }

    // =========================================
    // PRINT REPORT BUTTON
    // =========================================

    const printReportBtn = document.getElementById('printReportBtn');

    if (printReportBtn) {
        printReportBtn.addEventListener('click', () => {
            window.print();
        });
    }

})();

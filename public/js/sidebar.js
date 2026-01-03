(function () {
    let sidebar;
    let overlay;
    let hamburger;
    let mainWrapper;

    const isDesktop = () => window.innerWidth >= 1024;

    const lockBodyScroll = (shouldLock) => {
        document.body.style.overflow = shouldLock ? 'hidden' : '';
    };

    const setSidebarState = (shouldOpen) => {
        if (!sidebar) {
            return;
        }

        sidebar.classList.toggle('hidden', !shouldOpen);

        if (hamburger) {
            hamburger.classList.toggle('active', shouldOpen);
            hamburger.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        }

        if (overlay) {
            const showOverlay = shouldOpen && !isDesktop();
            overlay.classList.toggle('show', showOverlay);
        }

        if (mainWrapper) {
            if (shouldOpen && isDesktop()) {
                mainWrapper.classList.add('sidebar-open');
            } else {
                mainWrapper.classList.remove('sidebar-open');
            }
        }

        lockBodyScroll(shouldOpen && !isDesktop());
    };

    window.toggleSidebar = function (forceState) {
        if (!sidebar) {
            return;
        }

        const currentlyHidden = sidebar.classList.contains('hidden');
        const targetState = typeof forceState === 'boolean' ? forceState : currentlyHidden;
        setSidebarState(targetState);
    };

    const closeSidebarOnMobile = () => {
        if (!isDesktop()) {
            setSidebarState(false);
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        sidebar = document.getElementById('sidebar');
        if (!sidebar) {
            return;
        }

        overlay = document.getElementById('sidebarOverlay');
        hamburger = document.getElementById('hamburgerBtn') || document.getElementById('navbarToggleSidebar');
        mainWrapper = document.getElementById('mainWrapper');

        setSidebarState(isDesktop());

        if (overlay) {
            overlay.addEventListener('click', () => setSidebarState(false));
        }

        sidebar.querySelectorAll('.nav-link, .sidebar-nav-link').forEach((link) => {
            link.addEventListener('click', closeSidebarOnMobile);
        });

        document.addEventListener('click', (event) => {
            if (isDesktop() || sidebar.classList.contains('hidden')) {
                return;
            }

            const target = event.target;
            const clickedInsideSidebar = sidebar.contains(target);
            const clickedHamburger = hamburger && (hamburger === target || hamburger.contains(target));

            if (!clickedInsideSidebar && !clickedHamburger) {
                setSidebarState(false);
            }
        });

        window.addEventListener('resize', () => {
            setSidebarState(isDesktop());
        });
    });
})();

function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebarMobileEl = document.getElementById('toggleSidebarMobile');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const toggleSidebarMobileHamburger = document.getElementById('toggleSidebarMobileHamburger');
    const toggleSidebarMobileClose = document.getElementById('toggleSidebarMobileClose');

    if (!sidebar) {
        return;
    }

    const setSidebarOpen = (open) => {
        if (open) {
            sidebar.classList.remove('hidden');
            if (sidebarBackdrop) sidebarBackdrop.classList.remove('hidden');
            if (toggleSidebarMobileHamburger) toggleSidebarMobileHamburger.classList.add('hidden');
            if (toggleSidebarMobileClose) toggleSidebarMobileClose.classList.remove('hidden');
            if (toggleSidebarMobileEl) toggleSidebarMobileEl.setAttribute('aria-expanded', 'true');
        } else {
            sidebar.classList.add('hidden');
            if (sidebarBackdrop) sidebarBackdrop.classList.add('hidden');
            if (toggleSidebarMobileHamburger) toggleSidebarMobileHamburger.classList.remove('hidden');
            if (toggleSidebarMobileClose) toggleSidebarMobileClose.classList.add('hidden');
            if (toggleSidebarMobileEl) toggleSidebarMobileEl.setAttribute('aria-expanded', 'false');
        }
    };

    const toggleSidebar = () => {
        const isOpen = !sidebar.classList.contains('hidden');
        setSidebarOpen(!isOpen);
    };

    window.toggleStockifySidebar = toggleSidebar;
    window.setStockifySidebarOpen = setSidebarOpen;

    if (!document.body.dataset.sidebarDelegationAttached) {
        document.body.dataset.sidebarDelegationAttached = 'true';

        document.addEventListener('click', (e) => {
            const toggleBtn = e.target.closest('#toggleSidebarMobile');
            const backdrop = e.target.closest('#sidebarBackdrop');
            const searchBtn = e.target.closest('#toggleSidebarMobileSearch');

            if (toggleBtn) {
                e.preventDefault();
                toggleSidebar();
                return;
            }

            if (backdrop) {
                e.preventDefault();
                setSidebarOpen(false);
                return;
            }

            if (searchBtn) {
                e.preventDefault();
                toggleSidebar();
                return;
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('hidden')) {
                setSidebarOpen(false);
            }
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}



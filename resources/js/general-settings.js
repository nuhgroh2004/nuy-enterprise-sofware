// General Settings — macOS Style
(function () {

    // ===== Sidebar Tab Navigation =====
    const sidebarItems = document.querySelectorAll('.gs-sidebar-item[data-tab]');
    const tabs = document.querySelectorAll('.gs-tab');

    sidebarItems.forEach(item => {
        item.addEventListener('click', () => {
            const tabId = item.dataset.tab;

            sidebarItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            tabs.forEach(t => t.classList.remove('active'));
            const target = document.getElementById('tab-' + tabId);
            if (target) target.classList.add('active');
        });
    });

    // ===== Segmented Controls =====
    document.querySelectorAll('.gs-segmented').forEach(group => {
        group.querySelectorAll('.gs-seg').forEach(btn => {
            btn.addEventListener('click', () => {
                group.querySelectorAll('.gs-seg').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });

    // ===== Theme Selection =====
    document.querySelectorAll('.gs-theme-option').forEach(opt => {
        opt.addEventListener('click', () => {
            document.querySelectorAll('.gs-theme-option').forEach(o => o.classList.remove('active'));
            opt.classList.add('active');
        });
    });

    // ===== Color Swatch Selection =====
    document.querySelectorAll('.gs-color-swatch').forEach(swatch => {
        swatch.addEventListener('click', () => {
            document.querySelectorAll('.gs-color-swatch').forEach(s => s.classList.remove('active'));
            swatch.classList.add('active');
        });
    });

    // ===== Dock Auto-Hide Toggle =====
    const dockAutoHide = document.getElementById('dockAutoHide');
    if (dockAutoHide) {
        dockAutoHide.checked = localStorage.getItem('dockAutoHide') !== 'false';
        dockAutoHide.addEventListener('change', () => {
            localStorage.setItem('dockAutoHide', dockAutoHide.checked);
            const dockWrap = document.querySelector('.dock-wrap');
            if (dockWrap) {
                if (dockAutoHide.checked) {
                    dockWrap.classList.remove('always-show');
                } else {
                    dockWrap.classList.add('always-show');
                    dockWrap.classList.add('show');
                }
            }
        });
    }

    // ===== Search Filter =====
    const searchInput = document.getElementById('gsSearch');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            sidebarItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = (!q || text.includes(q)) ? '' : 'none';
            });

            // Show all tabs when searching
            if (q) {
                tabs.forEach(t => t.classList.add('active'));
            } else {
                tabs.forEach(t => t.classList.remove('active'));
                const activeItem = document.querySelector('.gs-sidebar-item.active[data-tab]');
                if (activeItem) {
                    const target = document.getElementById('tab-' + activeItem.dataset.tab);
                    if (target) target.classList.add('active');
                }
            }
        });
    }

})();

// Menghilangkan tooltip saat item dock diklik di perangkat mobile/touch
document.querySelectorAll('.dock-item[data-title]').forEach(item => {
  item.addEventListener('click', () => {
    item.blur();
  });
});

// Dock hover show/hide dengan touch support
(function () {
  const dockWrap = document.querySelector('.dock-wrap');
  if (!dockWrap) return;

  const autoHide = localStorage.getItem('dockAutoHide') !== 'false';

  if (autoHide) {
    dockWrap.classList.add('show');
  } else {
    dockWrap.classList.add('show');
    dockWrap.classList.add('always-show');
  }

  let hideTimeout;
  const TRIGGER_ZONE = 60;
  const INITIAL_SHOW = 2000;

  function showDock() {
    clearTimeout(hideTimeout);
    dockWrap.classList.add('show');
  }

  function scheduleHide(delay) {
    clearTimeout(hideTimeout);
    hideTimeout = setTimeout(() => {
      if (dockWrap.classList.contains('always-show')) return;
      dockWrap.classList.remove('show');
    }, delay || 250);
  }

  document.addEventListener('mousemove', (e) => {
    if (dockWrap.classList.contains('always-show')) return;
    const fromBottom = window.innerHeight - e.clientY;
    const sidebar = document.getElementById('sidebar');
    const overSidebar = sidebar && sidebar.contains(e.target);
    if ((fromBottom <= TRIGGER_ZONE || dockWrap.contains(e.target)) && !overSidebar) {
      showDock();
    } else if (!overSidebar) {
      scheduleHide();
    }
  });

  dockWrap.addEventListener('mouseleave', () => {
    if (dockWrap.classList.contains('always-show')) return;
    scheduleHide();
  });
  dockWrap.addEventListener('mouseenter', showDock);

  // Touch: tap to toggle
  if ('ontouchstart' in window) {
    document.addEventListener('touchstart', (e) => {
      if (dockWrap.classList.contains('always-show')) return;
      if (dockWrap.contains(e.target)) {
        showDock();
      } else {
        scheduleHide();
      }
    });
  }

  // Tampilkan dock 2 detik di awal page load
  showDock();
  scheduleHide(INITIAL_SHOW);
})();

// dock dot navigation highlight
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;
    const dockItems = document.querySelectorAll(
        '.dock-item[href]'
    );
    dockItems.forEach(item => {
        const href = item.getAttribute('href');
        if (!href || href === '#') {
            return;
        }
        const url = new URL(href, window.location.origin);
        const dockPath = url.pathname;
        const isActive = currentPath === dockPath || currentPath.startsWith(dockPath + '/');
        if (isActive) {
            item.classList.add('active');
            if (!item.querySelector('.dock-dot')) {
                const dot = document.createElement('span');
                dot.className = 'dock-dot';
                item.appendChild(dot);
            }
        } else {
            item.classList.remove('active');
            const dot = item.querySelector('.dock-dot');
            if (dot) {
                dot.remove();
            }
        }
    });
});

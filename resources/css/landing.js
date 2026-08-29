/**
 * ERP Suite — macOS-style landing page interactions
 * - Live menu bar clock (mimics real macOS menu bar)
 * - Dock hover magnification (distance-based, like macOS)
 * - Dock click -> swaps hero "window" screenshot per module
 * - Mobile nav toggle
 * - Scroll reveal for sections
 */

document.addEventListener('DOMContentLoaded', () => {
  initMenuBarClock();
  initDockMagnification();
  initModuleSwitcher();
  initMobileNav();
  initScrollReveal();
});

/* ---------- Menu bar clock ---------- */
function initMenuBarClock() {
  const clockEl = document.querySelector('[data-menu-clock]');
  if (!clockEl) return;

  const formatter = new Intl.DateTimeFormat('id-ID', {
    weekday: 'short',
    hour: '2-digit',
    minute: '2-digit',
  });

  const tick = () => {
    clockEl.textContent = formatter.format(new Date());
  };

  tick();
  setInterval(tick, 30000);
}

/* ---------- Dock magnification ---------- */
function initDockMagnification() {
  const dock = document.querySelector('[data-dock]');
  if (!dock) return;

  const items = Array.from(dock.querySelectorAll('.dock__item'));
  const MAX_SCALE = 1.35;
  const RANGE = 90; // px influence radius

  const reset = () => items.forEach((el) => (el.style.transform = 'scale(1)'));

  if (window.matchMedia('(hover: hover)').matches) {
    dock.addEventListener('mousemove', (e) => {
      const dockRect = dock.getBoundingClientRect();
      const mouseX = e.clientX - dockRect.left;

      items.forEach((el) => {
        const itemRect = el.getBoundingClientRect();
        const itemCenter = itemRect.left + itemRect.width / 2 - dockRect.left;
        const distance = Math.abs(mouseX - itemCenter);
        const influence = Math.max(0, 1 - distance / RANGE);
        const scale = 1 + influence * (MAX_SCALE - 1);
        el.style.transform = `scale(${scale.toFixed(3)})`;
      });
    });

    dock.addEventListener('mouseleave', reset);
  }
}

/* ---------- Module switcher (dock click swaps hero window content) ---------- */
function initModuleSwitcher() {
  const dockItems = document.querySelectorAll('[data-dock] .dock__item');
  const panels = document.querySelectorAll('[data-module-panel]');
  const titleEl = document.querySelector('[data-window-title]');

  if (!dockItems.length || !panels.length) return;

  dockItems.forEach((btn) => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-module');

      dockItems.forEach((b) => b.classList.toggle('is-active', b === btn));

      panels.forEach((panel) => {
        const match = panel.getAttribute('data-module-panel') === target;
        panel.style.display = match ? 'block' : 'none';
        panel.classList.toggle('is-visible', match);
      });

      if (titleEl) {
        titleEl.textContent = btn.getAttribute('data-window-label') || 'ERP Suite';
      }
    });
  });
}

/* ---------- Mobile nav ---------- */
function initMobileNav() {
  const toggle = document.querySelector('[data-nav-toggle]');
  const navbar = document.querySelector('[data-navbar]');
  if (!toggle || !navbar) return;

  toggle.addEventListener('click', () => {
    navbar.classList.toggle('is-open');
    const expanded = navbar.classList.contains('is-open');
    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
  });

  navbar.querySelectorAll('.navbar__mobile a').forEach((link) => {
    link.addEventListener('click', () => navbar.classList.remove('is-open'));
  });
}

/* ---------- Scroll reveal ---------- */
function initScrollReveal() {
  const targets = document.querySelectorAll('[data-reveal]');
  if (!targets.length) return;

  if (!('IntersectionObserver' in window)) {
    targets.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 }
  );

  targets.forEach((el) => observer.observe(el));
}

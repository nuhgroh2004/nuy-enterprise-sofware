// Menghilangkan tooltip saat item dock diklik di perangkat mobile/touch
document.querySelectorAll('.dock-item[data-title]').forEach(item => {
  item.addEventListener('click', () => {
    item.blur();
  });
});

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

        if (url.pathname === currentPath) {

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
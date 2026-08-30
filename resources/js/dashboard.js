const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  const toggle = document.getElementById('menuToggle');

  function openSidebar(){
    sidebar.classList.remove('hidden');
    sidebar.classList.add('open');
    backdrop.classList.add('show');
  }
  function closeSidebar(){
    sidebar.classList.add('hidden');
    sidebar.classList.remove('open');
    backdrop.classList.remove('show');
  }
  function isMobile(){return window.innerWidth <= 860;}
  toggle.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });
  document.querySelector('.traffic .c').addEventListener('click', closeSidebar);
  document.querySelector('.traffic .z').addEventListener('click', openSidebar);
  backdrop.addEventListener('click', closeSidebar);

  // Submenu toggle (for has-sub items that are not links)
  document.querySelectorAll('.navitem.has-sub').forEach(item => {
    item.addEventListener('click', (e) => {
      if(item.tagName !== 'A'){
        e.preventDefault();
        const submenuId = 'submenu-' + item.dataset.submenu;
        const submenu = document.getElementById(submenuId);

        if(submenu){
          item.classList.toggle('open');
          submenu.classList.toggle('open');

          if(submenu.classList.contains('open')){
            requestAnimationFrame(() => {
              const sidebarEl = document.getElementById('sidebar');
              const itemRect = item.getBoundingClientRect();
              const sidebarRect = sidebarEl.getBoundingClientRect();
              const submenuBottom = item.offsetTop + submenu.scrollHeight;

              if(submenuBottom > sidebarEl.scrollTop + sidebarRect.height){
                sidebarEl.scrollTo({
                  top: submenuBottom - sidebarRect.height + 16,
                  behavior: 'smooth'
                });
              }
            });
          }
        }
      }
    });
  });

  // Close sidebar on mobile when clicking navitem links
  document.querySelectorAll('.navitem:not(.has-sub), .subnavitem').forEach(item => {
    item.addEventListener('click', () => {
      if(window.innerWidth <= 860) closeSidebar();
    });
  });
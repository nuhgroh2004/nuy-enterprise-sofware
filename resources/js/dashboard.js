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

  // Submenu toggle
  document.querySelectorAll('.navitem.has-sub').forEach(item => {
    item.addEventListener('click', () => {
      const submenuId = 'submenu-' + item.dataset.submenu;
      const submenu = document.getElementById(submenuId);

      if(submenu){
        item.classList.toggle('open');
        submenu.classList.toggle('open');
      }
    });
  });

  // Submenu item click
  document.querySelectorAll('.subnavitem').forEach(item => {
    item.addEventListener('click', (e) => {
      e.stopPropagation();
      document.querySelectorAll('.subnavitem').forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      if(window.innerWidth <= 860) closeSidebar();
    });
  });

  // Main navitem click
  document.querySelectorAll('.navitem:not(.has-sub)').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelectorAll('.navitem:not(.has-sub)').forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      document.querySelectorAll('.subnavitem').forEach(i => i.classList.remove('active'));
      if(window.innerWidth <= 860) closeSidebar();
    });
  });

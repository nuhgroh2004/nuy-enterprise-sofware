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
  document.querySelectorAll('.navitem').forEach(item => {
    item.addEventListener('click', () => {
      if(window.innerWidth <= 860) closeSidebar();
    });
  });

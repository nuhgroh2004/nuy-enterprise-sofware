// Menghilangkan tooltip saat item dock diklik di perangkat mobile/touch
document.querySelectorAll('.dock-item[data-title]').forEach(item => {
  item.addEventListener('click', () => {
    item.blur();
  });
});
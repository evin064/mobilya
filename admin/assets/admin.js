const adminUserMenu = document.getElementById('admin-user-menu');
const adminUserToggle = document.getElementById('admin-user-toggle');
const adminUserDropdown = document.getElementById('admin-user-dropdown');

if (adminUserMenu && adminUserToggle && adminUserDropdown) {
  const closeMenu = () => {
    adminUserMenu.classList.remove('is-open');
    adminUserToggle.setAttribute('aria-expanded', 'false');
    adminUserDropdown.hidden = true;
  };

  const openMenu = () => {
    adminUserMenu.classList.add('is-open');
    adminUserToggle.setAttribute('aria-expanded', 'true');
    adminUserDropdown.hidden = false;
  };

  adminUserToggle.addEventListener('click', () => {
    if (adminUserMenu.classList.contains('is-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  document.addEventListener('click', (event) => {
    if (!adminUserMenu.contains(event.target)) {
      closeMenu();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMenu();
    }
  });
}

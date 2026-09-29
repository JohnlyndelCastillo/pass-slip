function toggleMenu(btn) {
  const dropdown = btn.nextElementSibling;
  const isOpen = dropdown.classList.contains('open');
  // Close all open dropdowns first
  document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
  if (!isOpen) {
    dropdown.classList.add('open');
    dropdown._anchorButton = btn;
    positionActionMenu(dropdown);
  }
}

function positionActionMenu(dropdown) {
  const btn = dropdown._anchorButton;
  if (!btn || !dropdown.classList.contains('open')) return;

  const buttonRect = btn.getBoundingClientRect();
  const menuRect = dropdown.getBoundingClientRect();
  const left = Math.max(8, Math.min(buttonRect.right - menuRect.width, window.innerWidth - menuRect.width - 8));
  let top = buttonRect.bottom + 4;
  if (top + menuRect.height > window.innerHeight - 8) {
    top = Math.max(8, buttonRect.top - menuRect.height - 4);
  }
  dropdown.style.left = `${left}px`;
  dropdown.style.top = `${top}px`;
}

function repositionOpenActionMenus() {
  document.querySelectorAll('.dropdown.open').forEach(positionActionMenu);
}

window.addEventListener('resize', repositionOpenActionMenus);
window.addEventListener('scroll', repositionOpenActionMenus, true);

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('.row-menu')) {
    document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
  }
});

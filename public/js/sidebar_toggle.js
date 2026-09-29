const sidebarLayout = document.querySelector('.side-layout');
const sidebarToggle = document.querySelector('.top-bar-menu');

if (sidebarLayout && sidebarToggle) {
  const storageKey = 'pass-slip-sidebar-collapsed';

  const setSidebarCollapsed = (collapsed) => {
    sidebarLayout.classList.toggle('sidebar-collapsed', collapsed);
    document.documentElement.classList.remove('sidebar-collapsed-initial');
    sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
    sidebarToggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
    sidebarToggle.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
  };

  try {
    setSidebarCollapsed(localStorage.getItem(storageKey) === 'true');
  } catch (error) {
    setSidebarCollapsed(false);
  }

  sidebarToggle.addEventListener('click', () => {
    const collapsed = !sidebarLayout.classList.contains('sidebar-collapsed');
    setSidebarCollapsed(collapsed);
    try {
      localStorage.setItem(storageKey, String(collapsed));
    } catch (error) {
      // Sidebar remains usable when browser storage is unavailable.
    }
  });
}

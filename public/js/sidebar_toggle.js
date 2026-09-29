const sidebarLayout = document.querySelector('.side-layout');
const sidebarToggle = document.querySelector('.top-bar-menu');

if (sidebarLayout && sidebarToggle) {
  const storageKey = 'pass-slip-sidebar-collapsed';
  const mobileQuery = window.matchMedia('(max-width: 768px)');

  const getSavedCollapsed = () => {
    try {
      return localStorage.getItem(storageKey) === 'true';
    } catch (error) {
      return false;
    }
  };

  const setSidebarCollapsed = (collapsed) => {
    sidebarLayout.classList.toggle('sidebar-collapsed', collapsed);
    document.documentElement.classList.remove('sidebar-collapsed-initial');
    sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
    sidebarToggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
    sidebarToggle.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
  };

  const setMobileNavOpen = (open) => {
    sidebarLayout.classList.toggle('mobile-nav-open', open);
    sidebarToggle.setAttribute('aria-expanded', String(open));
    sidebarToggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    sidebarToggle.title = open ? 'Close navigation' : 'Open navigation';
  };

  const syncViewportMode = () => {
    document.documentElement.classList.remove('sidebar-collapsed-initial');
    if (mobileQuery.matches) {
      sidebarLayout.classList.remove('sidebar-collapsed');
      setMobileNavOpen(false);
    } else {
      sidebarLayout.classList.remove('mobile-nav-open');
      setSidebarCollapsed(getSavedCollapsed());
    }
  };

  syncViewportMode();

  sidebarToggle.addEventListener('click', () => {
    if (mobileQuery.matches) {
      setMobileNavOpen(!sidebarLayout.classList.contains('mobile-nav-open'));
      return;
    }

    const collapsed = !sidebarLayout.classList.contains('sidebar-collapsed');
    setSidebarCollapsed(collapsed);
    try {
      localStorage.setItem(storageKey, String(collapsed));
    } catch (error) {
      // Sidebar remains usable when browser storage is unavailable.
    }
  });

  mobileQuery.addEventListener('change', syncViewportMode);

  document.addEventListener('click', (event) => {
    if (
      mobileQuery.matches &&
      sidebarLayout.classList.contains('mobile-nav-open') &&
      !event.target.closest('#dashboardSidebar') &&
      !event.target.closest('.top-bar-menu')
    ) {
      setMobileNavOpen(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && sidebarLayout.classList.contains('mobile-nav-open')) {
      setMobileNavOpen(false);
      sidebarToggle.focus();
    }
  });
}

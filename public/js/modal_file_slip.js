// Opening file slip modal
function openModal() {
  document.getElementById('createSlipModal').classList.add('open');
  history.pushState({ modal: 'create' }, '', CREATE_SLIP_URL);
}

function closeModal() {
  document.getElementById('createSlipModal').classList.remove('open');
  document.querySelector('#createSlipModal form').reset();
  clearSlipFormErrors();
  history.pushState({ modal: null }, '', DASHBOARD_URL);
}

function clearSlipFormErrors() {
  const form = document.getElementById('createSlipForm');
  if (!form) return;

  form.querySelectorAll('.form-error').forEach(el => {
    el.textContent = '';
  });
  form.querySelectorAll('.form-input, .form-select').forEach(el => {
    el.classList.remove('input-error');
  });
}

const createSlipModal = document.getElementById('createSlipModal');
if (createSlipModal) {
  createSlipModal.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
}

// Validate the create-slip form before it submits.
// This is a UX convenience only — the real check that can't be bypassed
// lives server-side in auth/create_slip.php, since a user can always
// disable JS or send a raw POST request.
const createSlipForm = document.getElementById('createSlipForm');
if (createSlipForm) {
  createSlipForm.addEventListener('submit', function(e) {
    let hasError = false;

    // Clear old error messages first
    clearSlipFormErrors();

    const requiredFields = createSlipForm.querySelectorAll('[required]');

    requiredFields.forEach(field => {
      const isEmpty = !field.value || field.value.trim() === '';
      if (isEmpty) {
        hasError = true;
        field.classList.add('input-error');
        const errorEl = createSlipForm.querySelector(`[data-error-for="${field.name}"]`);
        if (errorEl) {
          errorEl.textContent = 'This field is required.';
        }
      }
    });

    if (hasError) {
      e.preventDefault();
    }
  });

  // Clear a field's own error the moment the user fixes it,
  // rather than waiting for the next submit attempt.
  createSlipForm.querySelectorAll('.form-input, .form-select').forEach(field => {
    const eventType = field.tagName === 'SELECT' ? 'change' : 'input';
    field.addEventListener(eventType, function() {
      if (field.value && field.value.trim() !== '') {
        field.classList.remove('input-error');
        const errorEl = createSlipForm.querySelector(`[data-error-for="${field.name}"]`);
        if (errorEl) {
          errorEl.textContent = '';
        }
      }
    });
  });
}

// Keep the modal in sync with browser back/forward
window.addEventListener('popstate', function() {
  const modal = document.getElementById('createSlipModal');
  if (!modal) return;

  if (window.location.pathname.endsWith('/create')) {
    modal.classList.add('open');
  } else {
    modal.classList.remove('open');
  }
});

// If the page was loaded directly on /create, JS can't rely on PHP alone
// (e.g. if the initial "open" class is added conditionally server-side,
// this is optional — safe to keep as a fallback either way)
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('createSlipModal');
  if (modal && window.location.pathname.endsWith('/create')) {
    modal.classList.add('open');
  }
});

// Open profile dropdown
function toggleProfileMenu(event) {
  event.stopPropagation();
  const dropdown = document.getElementById('profileDropdown');
  if (dropdown) dropdown.classList.toggle('open');
}

// Opening slip details modal
function openDetailsModal(el) {
  const d = el.dataset;

  document.getElementById('detail-category').textContent = d.category;
  document.getElementById('detail-student').textContent  = d.student;
  document.getElementById('detail-section').textContent  = d.section;
  document.getElementById('detail-date').textContent     = d.date;
  document.getElementById('detail-time').textContent     = d.time;
  document.getElementById('detail-purpose').textContent  = d.purpose;
  document.getElementById('detail-adviser').textContent  = d.adviser;
  document.getElementById('detail-techhead').textContent = d.techhead;
  document.getElementById('detail-note').textContent     = d.note;
  document.getElementById('detail-created').textContent  = d.created;

  const statusEl = document.getElementById('detail-status');
  statusEl.textContent = d.status
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
  statusEl.className = `badge badge-${d.status}`;

  document.getElementById('slipDetailsModal').classList.add('open');
}

function closeDetailsModal() {
  document.getElementById('slipDetailsModal').classList.remove('open');
}

const slipDetailsModal = document.getElementById('slipDetailsModal');
if (slipDetailsModal) {
  slipDetailsModal.addEventListener('click', function(e) {
    if (e.target === this) closeDetailsModal();
  });
}

// Notification dropdown
const bellBtn = document.querySelector('.top-bar-bell');
const notifDropdown = document.getElementById('notificationDropdown');

if (bellBtn && notifDropdown) {
  bellBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    notifDropdown.classList.toggle('open');
    const profileDropdown = document.getElementById('profileDropdown');
    if (profileDropdown) profileDropdown.classList.remove('open');
  });
}

// Profile dropdown
const avatarBtn = document.querySelector('.top-bar-avatar');
const profileDropdown = document.getElementById('profileDropdown');

if (avatarBtn && profileDropdown) {
  avatarBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    profileDropdown.classList.toggle('open');
    if (notifDropdown) notifDropdown.classList.remove('open');
  });
}

// Close both when clicking outside
document.addEventListener('click', function() {
  if (notifDropdown) notifDropdown.classList.remove('open');
  if (profileDropdown) profileDropdown.classList.remove('open');
});

// Notification dropdown
function toggleNotifications(event) {
  event.stopPropagation();
  const dropdown = document.getElementById('notificationDropdown');
  if (dropdown) dropdown.classList.toggle('open');
}

function markNotificationRead(el) {
  const id = el.dataset.id;
  if (!el.classList.contains('unread')) return;

  fetch(NOTIFICATIONS_READ_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}`
  }).then(() => {
    el.classList.remove('unread');
    const badge = document.querySelector('.notification-badge');
    if (badge) {
      const count = parseInt(badge.textContent) - 1;
      if (count <= 0) {
        badge.remove();
      } else {
        badge.textContent = count;
      }
    }
  });
}
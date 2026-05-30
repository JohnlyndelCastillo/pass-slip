// Opening file slip modal
function openModal() {
  document.getElementById('createSlipModal').classList.add('open');
}

function closeModal() {
  document.getElementById('createSlipModal').classList.remove('open');
  document.querySelector('#createSlipModal form').reset();
}

const createSlipModal = document.getElementById('createSlipModal');
if (createSlipModal) {
  createSlipModal.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
}

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

  fetch('/auth/notifications/mark_notifications_read.php', {
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
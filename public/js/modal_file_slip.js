// Opening file slip modal
function openModal() {
  document.getElementById('createSlipModal').classList.add('open');
}

function closeModal() {
  document.getElementById('createSlipModal').classList.remove('open');
  document.querySelector('#createSlipModal form').reset();
}

// Close when clicking outside the modal box
document.getElementById('createSlipModal').addEventListener('click', function (e) {
  if (e.target === this) closeModal();
});

// Open profile dropdown
function toggleProfileMenu(event) {
  event.stopPropagation();
  const dropdown = document.getElementById('profileDropdown');
  if (dropdown) dropdown.classList.toggle('open');
}

// Close when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('.top-bar-avatar')) {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) dropdown.classList.remove('open');
  }
});

// Opening slip details modal
function openDetailsModal(el) {
  const d = el.dataset;

  document.getElementById('detail-category').textContent = d.category;
  document.getElementById('detail-student').textContent = d.student;
  document.getElementById('detail-section').textContent = d.section;
  document.getElementById('detail-date').textContent = d.date;
  document.getElementById('detail-time').textContent = d.time;
  document.getElementById('detail-purpose').textContent = d.purpose;
  document.getElementById('detail-adviser').textContent = d.adviser;
  document.getElementById('detail-techhead').textContent = d.techhead;
  document.getElementById('detail-note').textContent = d.note;
  document.getElementById('detail-status').textContent = d.status;
  document.getElementById('detail-created').textContent = d.created;

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

// Close when clicking outside
document.getElementById('slipDetailsModal').addEventListener('click', function (e) {
  if (e.target === this) closeDetailsModal();
});

function toggleNotifications(event) {
  event.stopPropagation();
  const dropdown = document.getElementById('notificationDropdown');
  if (dropdown) dropdown.classList.toggle('open');
}

// Close when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('.top-bar-bell')) {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) dropdown.classList.remove('open');
  }
});

function markNotificationRead(el) {
  const id = el.dataset.id;

  if (!el.classList.contains('unread')) return; // already read

  fetch('/auth/notifications/mark_notifications_read.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}`
  }).then(() => {
    // Remove unread styling
    el.classList.remove('unread');

    // Update badge count
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

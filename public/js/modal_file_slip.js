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
function toggleProfileMenu() {
  const dropdown = document.getElementById('profileDropdown');
  dropdown.classList.toggle('open');
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
  statusEl.textContent = d.status.charAt(0).toUpperCase() + d.status.slice(1);
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
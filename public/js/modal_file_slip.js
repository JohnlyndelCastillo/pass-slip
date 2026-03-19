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
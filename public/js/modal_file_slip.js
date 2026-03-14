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
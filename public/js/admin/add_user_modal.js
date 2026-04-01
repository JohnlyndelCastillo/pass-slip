function openCreateUserModal() {
  document.getElementById('createUserModal').classList.add('open');
}

function closeCreateUserModal() {
  document.getElementById('createUserModal').classList.remove('open');
  document.querySelector('#createUserModal form').reset();
}

function openEditUserModal(id, fullname, username, role) {
  document.getElementById('edit-user-id').value = id;
  document.getElementById('edit-fullname').value = fullname;
  document.getElementById('edit-username').value = username;
  document.getElementById('edit-role').value = role;
  document.getElementById('editUserModal').classList.add('open');
}

function closeEditUserModal() {
  document.getElementById('editUserModal').classList.remove('open');
}

// Close when clicking outside
const createUserModal = document.getElementById('createUserModal');
if (createUserModal) {
  createUserModal.addEventListener('click', function (e) {
    if (e.target === this) closeCreateUserModal();
  });
}

const editUserModal = document.getElementById('editUserModal');
if (editUserModal) {
  editUserModal.addEventListener('click', function (e) {
    if (e.target === this) closeEditUserModal();
  });
}

const messages = document.querySelectorAll('.flash-message');

messages.forEach(msg => {
  setTimeout(() => {
    msg.style.transition = 'opacity 0.5s ease';
    msg.style.opacity = '0';

    setTimeout(() => {
      msg.remove();
    }, 500);
  }, 3000); // visible for 3 seconds
});
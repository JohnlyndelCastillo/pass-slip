const createUserModalEl = document.getElementById('createUserModal');
const editUserModalEl = document.getElementById('editUserModal');

const CREATE_USER_URL = createUserModalEl?.dataset.createUrl;
const EDIT_USER_URL = editUserModalEl?.dataset.editUrl;
const ADMIN_DASHBOARD_URL = createUserModalEl?.dataset.dashboardUrl || editUserModalEl?.dataset.dashboardUrl;

function openCreateUserModal() {
  document.getElementById('createUserModal').classList.add('open');
  if (CREATE_USER_URL) {
    history.pushState({ modal: 'create-user' }, '', CREATE_USER_URL);
  }
}

function closeCreateUserModal() {
  document.getElementById('createUserModal').classList.remove('open');
  document.querySelector('#createUserModal form').reset();
  if (ADMIN_DASHBOARD_URL) {
    history.pushState({ modal: null }, '', ADMIN_DASHBOARD_URL);
  }
}

function openEditUserModal(id, fullname, username, role) {
  document.getElementById('edit-user-id').value = id;
  document.getElementById('edit-fullname').value = fullname;
  document.getElementById('edit-username').value = username;
  document.getElementById('edit-role').value = role;
  document.getElementById('editUserModal').classList.add('open');
  if (EDIT_USER_URL) {
    history.pushState({ modal: 'edit-user', id }, '', `${EDIT_USER_URL}?id=${id}`);
  }
}

function closeEditUserModal() {
  document.getElementById('editUserModal').classList.remove('open');
  if (ADMIN_DASHBOARD_URL) {
    history.pushState({ modal: null }, '', ADMIN_DASHBOARD_URL);
  }
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

// Keep modals in sync with browser back/forward
window.addEventListener('popstate', function () {
  const path = window.location.pathname;

  if (createUserModal) {
    if (path.endsWith('/create')) {
      createUserModal.classList.add('open');
    } else {
      createUserModal.classList.remove('open');
    }
  }

  if (editUserModal) {
    if (path.endsWith('/edit')) {
      editUserModal.classList.add('open');
    } else {
      editUserModal.classList.remove('open');
    }
  }
});

// Direct loads on /create or /edit?id= — PHP already renders the
// correct initial "open" class server-side, this is just a fallback
document.addEventListener('DOMContentLoaded', function () {
  const path = window.location.pathname;

  if (createUserModal && path.endsWith('/create')) {
    createUserModal.classList.add('open');
  }

  if (editUserModal && path.endsWith('/edit')) {
    editUserModal.classList.add('open');
  }
});

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
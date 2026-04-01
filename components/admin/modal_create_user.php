<div class="modal-overlay" id="createUserModal">
  <div class="modal">
    <div class="modal-header">
      <h2>Add New User</h2>
      <button class="modal-close" onclick="closeCreateUserModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="modal-divider"></div>
    <div class="modal-body">
      <form action="/auth/admin/create_user.php" method="POST">
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Full Name</label>
          <input type="text" name="fullname" class="form-input" placeholder="Enter full name" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-input" placeholder="Enter username" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-input" placeholder="Enter password" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" required>
            <option value="" disabled selected>Select role</option>
            <option value="instructor">Instructor</option>
            <option value="adviser">Adviser</option>
            <option value="technology_head">Technology Head</option>
            <option value="csd_council">CSD Council</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeCreateUserModal()">Cancel</button>
          <button type="submit" class="btn-create">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
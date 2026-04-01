<div class="modal-overlay" id="editUserModal">
  <div class="modal">
    <div class="modal-header">
      <h2>Edit User</h2>
      <button class="modal-close" onclick="closeEditUserModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="modal-divider"></div>
    <div class="modal-body">
      <form action="/auth/admin/edit_user.php" method="POST">
        <input type="hidden" name="id" id="edit-user-id">
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Full Name</label>
          <input type="text" name="fullname" id="edit-fullname" class="form-input" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Username</label>
          <input type="text" name="username" id="edit-username" class="form-input" required>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">New Password <span style="color:#6b7280; font-size:12px;">(leave blank to keep current)</span></label>
          <input type="password" name="password" class="form-input" placeholder="Enter new password">
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
          <label class="form-label">Role</label>
          <select name="role" id="edit-role" class="form-select" required>
            <option value="instructor">Instructor</option>
            <option value="adviser">Adviser</option>
            <option value="technology_head">Technology Head</option>
            <option value="csd_council">CSD Council</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeEditUserModal()">Cancel</button>
          <button type="submit" class="btn-create">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
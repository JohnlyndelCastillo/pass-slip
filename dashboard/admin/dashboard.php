<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';

// Fetch all non-admin users
$stmt = $conn->prepare("SELECT id, fullname, username, role, created_at FROM users WHERE role != 'admin'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/student_request_page_style.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

  <!-- Top Bar -->
  <header class="top-bar">
    <div class="top-bar-menu">
      <i class="fa-solid fa-bars"></i>
    </div>
    <div class="top-bar-spacer"></div>
    <div class="top-bar-actions">
      <div class="top-bar-bell" title="Notifications">
        <i class="fa-solid fa-bell"></i>
      </div>
      <div class="top-bar-avatar" title="Profile" onclick="toggleProfileMenu()">
        <i class="fa-solid fa-user"></i>
        <?php include __DIR__ . '/../../components/profile_actions.php'; ?>
      </div>
    </div>
  </header>

  <!-- Side Layout -->
  <div class="side-layout">
    <aside class="side-bar">
      <a class="nav-item active" href="/dashboard/admin/dashboard.php">
        <i class="fa-solid fa-users"></i>
        Manage Users
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

      <?php if (!empty($_SESSION['userError'])): ?>
        <p class="flash-message form-error">
          <?= htmlspecialchars($_SESSION['userError']) ?>
        </p>
        <?php unset($_SESSION['userError']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['userSuccess'])): ?>
        <p class="flash-message form-success">
          <?= htmlspecialchars($_SESSION['userSuccess']) ?>
        </p>
        <?php unset($_SESSION['userSuccess']); ?>
      <?php endif; ?>
      <div class="page-header">
        <h1 class="page-title">Manage Users</h1>
        <button class="btn-file-slip" onclick="openCreateUserModal()">
          <i class="fa-solid fa-plus"></i>
          Add User
        </button>
      </div>

      <!-- Table -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Username</th>
              <th>Role</th>
              <th>Created At</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['fullname']) ?></td>
                  <td><?= htmlspecialchars($row['username']) ?></td>
                  <td><span class="badge role-<?= $row['role'] ?>"><?= ucwords(str_replace('_', ' ', $row['role'])) ?></span></td>
                  <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                  <td class="row-menu">
                    <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                    <div class="dropdown">
                      <a href="#" onclick="openEditUserModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['fullname']) ?>', '<?= htmlspecialchars($row['username']) ?>', '<?= $row['role'] ?>')">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                      </a>
                      <form action="/auth/admin/delete_user.php" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this user?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="dropdown-delete">
                          <i class="fa-regular fa-trash-can"></i> Delete
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="text-align:center; color:#6b7280; padding: 24px;">
                  No users found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>

  <?php include __DIR__ . '/../../components/admin/modal_create_user.php'; ?>
  <?php include __DIR__ . '/../../components/admin/modal_edit_user.php'; ?>

  <script src="/public/js/show_action_menu.js"></script>
  <script src="/public/js/modal_file_slip.js"></script>
  <script src="/public/js/admin/add_user_modal.js"></script>

</body>

</html>
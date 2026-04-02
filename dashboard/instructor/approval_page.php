<?php
$allowedRoles = ['instructor'];
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../components/table_actions.php';
require_once __DIR__ . '/../../includes/db.php';

$stmt = $conn->prepare("SELECT ps.*, u.fullname FROM pass_slips ps JOIN users u ON ps.user_id = u.id WHERE ps.approval_status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approval Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/common.css?v=1.0">
  <link rel="stylesheet" href="/public/css/instructor/instructor_approval_page_style.css?v=1.0">
  <link rel="stylesheet" href="/public/css/form/form_common_style.css?v=1.0">
  <link rel="stylesheet" href="/public/css/modal/modal_component_style.css?v=1.0">
  <link rel="stylesheet" href="/public/css/approval-badge/approval_status_badge_style.css?v=1.0">
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
      <a class="nav-item active" href="#">
        <i class="fa-solid fa-hashtag"></i>
        Dashboard
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

      <?php if (!empty($_SESSION['approvalError'])): ?>
        <p class="form-error"><?= htmlspecialchars($_SESSION['approvalError']) ?></p>
        <?php unset($_SESSION['approvalError']); ?>
      <?php endif; ?>

      <div class="page-header">
        <h1 class="page-title">Request List</h1>
      </div>

      <!-- Table -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Request Date</th>
              <th>Requester</th>
              <th>Actions</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td class="row-title">
                    <a href="#" onclick="openDetailsModal(this)"
                      data-category="<?= ucfirst(htmlspecialchars($row['category'])) ?>"
                      data-student="<?= htmlspecialchars($row['requesting_student']) ?>"
                      data-section="<?= htmlspecialchars($row['section'] ?? '—') ?>"
                      data-date="<?= $row['request_date'] ?>"
                      data-time="<?= date('h:i A', strtotime($row['request_time'])) ?>"
                      data-purpose="<?= htmlspecialchars($row['purpose']) ?>"
                      data-adviser="<?= htmlspecialchars($row['class_adviser'] ?? '—') ?>"
                      data-techhead="<?= htmlspecialchars($row['technology_head'] ?? '—') ?>"
                      data-note="<?= htmlspecialchars($row['note'] ?? '—') ?>"
                      data-status="<?= $row['approval_status'] ?>"
                      data-created="<?= date('M d, Y', strtotime($row['created_at'])) ?>">
                      <?= htmlspecialchars($row['purpose']) ?>
                    </a>
                  </td>
                  <td><?= date('Y-m-d', strtotime($row['request_date'])) ?></td>
                  <td><?= htmlspecialchars($row['section'] ?? '—') ?></td>
                  <td>
                    <div class="row-actions">
                      <form action="/auth/approve_slip.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-approve">
                          <i class="fa-solid fa-check"></i> Approve
                        </button>
                      </form>
                      <form action="/auth/reject_slip.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-reject"
                          onclick="return confirm('Are you sure you want to reject this slip?')">
                          <i class="fa-solid fa-x"></i> Reject
                        </button>
                      </form>
                    </div>
                  </td>
                  <td class="row-menu">
                    <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                    <?php rowDropdown('#', $row['id']); ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="text-align:center; color:#6b7280; padding: 24px;">
                  No pending requests found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>

  <?php include __DIR__ . '/../../components/modal_slip_details.php'; ?>

  <script src="/public/js/show_action_menu.js"></script>
  <script src="/public/js/modal_file_slip.js"></script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Approval Page</title>
  <script>try { if (window.matchMedia('(min-width: 769px)').matches && localStorage.getItem('pass-slip-sidebar-collapsed') === 'true') document.documentElement.classList.add('sidebar-collapsed-initial'); } catch (error) {}</script>
  <link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/public/css/common.css?v=1.8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="/public/css/modal/profile_dropdown_style.css?v=1.0">
</head>

<?php
$approvalDashboardPaths = [
  'instructor' => '/dashboard/instructor',
  'adviser' => '/dashboard/adviser',
  'technology_head' => '/dashboard/technology-head',
  'csd_council' => '/dashboard/csd-council',
];
$approvalDashboardPath = $approvalDashboardPaths[$role] ?? '/login';
?>

<body data-user-id="<?= (int) $_SESSION['user_id'] ?>">

  <!-- Top Bar -->
  <header class="top-bar">
    <button class="top-bar-menu" type="button" aria-label="Collapse sidebar" aria-expanded="true" aria-controls="dashboardSidebar">
      <i class="fa-solid fa-bars"></i>
    </button>
    <div class="top-bar-spacer"></div>
    <div class="top-bar-actions">
      <div class="top-bar-bell" title="Notifications">
        <i class="fa-solid fa-bell"></i>
        <?php if ($unreadCount > 0): ?>
          <span class="notification-badge"><?= $unreadCount ?></span>
        <?php endif; ?>
        <?php include __DIR__ . '/../notifications_dropdown.php'; ?>
      </div>
      <div class="top-bar-avatar" title="Profile">
        <i class="fa-solid fa-user"></i>
        <?php include __DIR__ . '/../profile_actions.php'; ?>
      </div>
    </div>
  </header>

  <!-- Side Layout -->
  <div class="side-layout">
    <aside class="side-bar" id="dashboardSidebar" aria-label="Main navigation">
      <a class="nav-item active" href="<?= url($approvalDashboardPath) ?>" title="Approvals" aria-label="Approvals" aria-current="page">
        <i class="fa-solid fa-list-check"></i>
        Approvals
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

      <?php if (!empty($_SESSION['approvalError'])): ?>
        <p class="form-error"><?= htmlspecialchars($_SESSION['approvalError']) ?></p>
        <?php unset($_SESSION['approvalError']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['approvalSuccess'])): ?>
        <p class="form-success"><?= htmlspecialchars($_SESSION['approvalSuccess']) ?></p>
        <?php unset($_SESSION['approvalSuccess']); ?>
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
              <th>Section</th>
              <th>Actions</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td class="row-title" data-label="Title">
                    <a href="#" onclick="openDetailsModal(this)"
                      data-category="<?= ucfirst(htmlspecialchars($row['category'])) ?>"
                      data-student="<?= htmlspecialchars($row['requesting_student']) ?>"
                      data-section="<?= htmlspecialchars($row['section'] ?? '—') ?>"
                      data-date="<?= date('M d, Y', strtotime($row['request_date'])) ?>"
                      data-time="<?= date('h:i A', strtotime($row['request_time'])) ?>"
                      data-purpose="<?= htmlspecialchars($row['purpose']) ?>"
                      data-adviser="<?= htmlspecialchars($row['adviser_name'] ?? '—') ?>"
                      data-techhead="<?= htmlspecialchars($row['techhead_name'] ?? '—') ?>"
                      data-note="<?= htmlspecialchars($row['note'] ?? '—') ?>"
                      data-status="<?= $row['approval_status'] ?>"
                      data-created="<?= date('M d, Y', strtotime($row['created_at'])) ?>">
                      <?= htmlspecialchars($row['purpose']) ?>
                    </a>
                  </td>
                  <td data-label="Request Date"><?= date('M d, Y', strtotime($row['request_date'])) ?></td>
                  <td data-label="Requester"><?= htmlspecialchars($row['student_name']) ?></td>
                  <td data-label="Section"><?= htmlspecialchars($row['section'] ?? '—') ?></td>
                  <td data-label="Actions">
                    <div class="row-actions">
                      <form action="<?= url('/slips/approve') ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-approve">
                          <i class="fa-solid fa-check"></i> Approve
                        </button>
                      </form>
                      <form action="<?= url('/slips/reject') ?>" method="POST">
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
                <td colspan="6" style="text-align:center; color:#6b7280; padding: 24px;">
                  No pending requests found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>

  <?php include __DIR__ . '/../modal_slip_details.php'; ?>

  <script src="/public/js/show_action_menu.js"></script>
  <script src="/public/js/modal_file_slip.js"></script>
  <script src="/public/js/resize_table_columns.js?v=<?= filemtime(dirname(__DIR__, 2) . '/public/js/resize_table_columns.js') ?>"></script>
  <script src="/public/js/sidebar_toggle.js?v=1.1"></script>

</body>
</html>

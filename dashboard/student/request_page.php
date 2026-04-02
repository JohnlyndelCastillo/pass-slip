<?php
$allowedRoles = ['student'];
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../components/table_actions.php';
require_once __DIR__ . '/../../includes/db.php';

$sections = json_decode(file_get_contents(__DIR__ . '/../../api/data/sections.json'));

$adviserStmt = $conn->prepare("SELECT id, fullname FROM users WHERE role = 'adviser'");
$adviserStmt->execute();
$advisers = $adviserStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$techHeadStmt = $conn->prepare("SELECT id, fullname FROM users WHERE role = 'technology_head'");
$techHeadStmt->execute();
$techHeads = $techHeadStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("
  SELECT ps.*, 
    u.fullname AS reviewed_by_name,
    a.fullname AS adviser_name,
    t.fullname AS techhead_name
  FROM pass_slips ps
  LEFT JOIN users u ON ps.reviewed_by = u.id
  LEFT JOIN users a ON ps.class_adviser = a.id
  LEFT JOIN users t ON ps.technology_head = t.id
  WHERE ps.user_id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Pass Slip</title>
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

  <!-- Side Bar -->
  <div class="side-layout">
    <aside class="side-bar">
      <a class="nav-item active" href="#">
        <i class="fa-solid fa-hashtag"></i>
        Dashboard
      </a>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

      <?php if (!empty($_SESSION['slipError'])): ?>
        <p class="form-error"><?= htmlspecialchars($_SESSION['slipError']) ?></p>
        <?php unset($_SESSION['slipError']); ?>
      <?php endif; ?>

      <div class="page-header">
        <h1 class="page-title">Requests</h1>
        <button class="btn-file-slip" onclick="openModal()">
          <i class="fa-solid fa-plus"></i>
          File Slip
        </button>
      </div>

      <!-- Table for displaying requests -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Title</th>
              <th>Creation Date</th>
              <th>Status</th>
              <th>Status Date</th>
              <th>Reviewed By</th>
              <th> </th>
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
                  <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                  <td><span class="badge badge-<?= $row['approval_status'] ?>"><?= ucfirst($row['approval_status']) ?></span></td>
                  <td><?= $row['status_date'] ?? '—' ?></td>
                  <td><?= htmlspecialchars($row['reviewed_by_name'] ?? '—') ?></td>
                  <td class="row-menu">
                    <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                    <?php rowDropdown('edit.php?id=' . $row['id'], $row['id']); ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align:center; color:#6b7280; padding: 24px;">
                  No requests found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <?php include __DIR__ . '/../../components/modal_create_pass_slip.php'; ?>
  <?php include __DIR__ . '/../../components/modal_slip_details.php'; ?>

  <script src="/public/js/show_action_menu.js"></script>
  <script src="/public/js/modal_file_slip.js"></script>
</body>

</html>
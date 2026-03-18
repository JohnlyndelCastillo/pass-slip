<?php
require_once __DIR__ . '/../middleware/auth_guard.php';
require_once __DIR__ . '/../components/table_actions.php';
require_once __DIR__ . '/../includes/db.php';

$stmt = $conn->prepare("SELECT * FROM pass_slips WHERE user_id = ?");
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
      <div class="top-bar-avatar" title="Profile">
        <i class="fa-solid fa-user"></i>
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
              <th>Approval Status</th>
              <th>Approval Date</th>
              <th>Approved By</th>
              <th> </th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td class="row-title"><a href="#"><?= htmlspecialchars($row['purpose']) ?></a></td>
                  <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                  <td><span class="badge badge-<?= $row['approval_status'] ?>"><?= ucfirst($row['approval_status']) ?></span></td>
                  <td><?= $row['approval_date'] ?? '—' ?></td>
                  <td><?= htmlspecialchars($row['approved_by'] ?? '—') ?></td>
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

  <?php include __DIR__ . '/../components/modal_create_pass_slip.php'; ?>

  <script src="/public/js/show_action_menu.js"></script>
  <script src="/public/js/modal_file_slip.js"></script>
</body>
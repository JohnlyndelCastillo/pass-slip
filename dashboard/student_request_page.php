<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: /dashboard/login.php");
  exit;
}
require_once '../components/table_actions.php';
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
      <div class="page-header">
        <h1 class="page-title">Requests</h1>
        <button class="btn-file-slip">
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
            <tr>
              <td class="row-title"><a href="#">32H Early Out to Leave Class</a></td>
              <td>2024-06-01</td>
              <td><span class="badge badge-pending">Pending</span></td>
              <td>2024-06-01</td>
              <td>John Doe</td>
              <td class="row-menu">
                <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                <?php rowDropdown('edit.php?id=1', 'delete.php?id=1'); ?>
              </td>
            </tr>
            <tr>
              <td class="row-title"><a href="#">32H Early Out to Leave Class</a></td>
              <td>2024-06-02</td>
              <td><span class="badge badge-approved">Approved</span></td>
              <td>2024-06-02</td>
              <td>John Doe</td>
              <td class="row-menu">
                <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                <?php rowDropdown('edit.php?id=1', 'delete.php?id=1'); ?>
              </td>
            </tr>
            <tr>
              <td class="row-title"><a href="#">32H Early Out to Leave Class</a></td>
              <td>2024-06-03</td>
              <td><span class="badge badge-rejected">Rejected</span></td>
              <td>2024-06-03</td>
              <td>John Doe</td>
              <td class="row-menu">
                <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                <?php rowDropdown('edit.php?id=1', 'delete.php?id=1'); ?>
              </td>
            </tr>
            <tr class="empty">
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td class="row-menu">
                <button class="row-menu-btn" onclick="toggleMenu(this)">⋮</button>
                <?php rowDropdown('edit.php?id=2', 'delete.php?id=2'); ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  </div>
  <script src="/public/js/show_action_menu.js"></script>
</body>
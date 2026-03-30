<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: /dashboard/login.php");
  exit;
}

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id            = $_POST['id'];
  $approved_by   = $_SESSION['user_id'];
  $approval_date = date('Y-m-d');

  $stmt = $conn->prepare("
    UPDATE pass_slips 
    SET approval_status = 'rejected', approved_by = ?, approval_date = ?
    WHERE id = ?
  ");
  $stmt->bind_param("isi", $approved_by, $approval_date, $id);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: /dashboard/instructor/approval_page.php");
    exit;
  } else {
    $_SESSION['approvalError'] = "Failed to reject slip.";
    header("Location: /dashboard/instructor/approval_page.php");
    exit;
  }
}

header("Location: /dashboard/instructor/approval_page.php");
exit;

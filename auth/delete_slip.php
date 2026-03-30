<?php
require_once __DIR__ . '/../middleware/auth_guard.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id      = $_POST['id'];
  $user_id = $_SESSION['user_id'];

  // Make sure the slip belongs to the logged in user
  $stmt = $conn->prepare("DELETE FROM pass_slips WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $user_id);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: /dashboard/student/request_page.php");
    exit;
  } else {
    $_SESSION['slipError'] = "Failed to delete. Please try again.";
    header("Location: /dashboard/student/request_page.php");
    exit;
  }
}

// Block direct access
header("Location: /dashboard/student/request_page.php");
exit;

<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];

  $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    $_SESSION['userSuccess'] = "User deleted successfully.";
  } else {
    $_SESSION['userError'] = "Failed to delete user.";
  }

  $stmt->close();
  $conn->close();
  header("Location: /../../dashboard/admin/dashboard.php");
  exit;
}

header("Location: /../../dashboard/admin/dashboard.php");
exit;

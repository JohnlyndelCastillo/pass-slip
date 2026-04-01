<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id       = $_POST['id'];
  $fullname = trim($_POST['fullname']);
  $username = trim($_POST['username']);
  $role     = $_POST['role'];
  $password = $_POST['password'];

  if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, password=?, role=? WHERE id=?");
    $stmt->bind_param("ssssi", $fullname, $username, $hashed, $role, $id);
  } else {
    $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $fullname, $username, $role, $id);
  }

  if ($stmt->execute()) {
    $_SESSION['userSuccess'] = "User updated successfully.";
  } else {
    $_SESSION['userError'] = "Failed to update user.";
  }

  $stmt->close();
  $conn->close();
  header("Location: /../../dashboard/admin/dashboard.php");
  exit;
}

header("Location: /../../dashboard/admin/dashboard.php");
exit;

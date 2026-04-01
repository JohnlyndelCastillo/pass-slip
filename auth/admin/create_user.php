<?php
$allowedRoles = ['admin'];
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = trim($_POST['fullname']);
  $username = trim($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role     = $_POST['role'];

  $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, role) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $fullname, $username, $password, $role);

  if ($stmt->execute()) {
    $_SESSION['userSuccess'] = "User created successfully.";
  } else {
    $_SESSION['userError'] = "Failed to create user. Username may already exist.";
  }

  $stmt->close();
  $conn->close();
  header("Location: /../../dashboard/admin/dashboard.php");
  exit;
}

header("Location: /../../dashboard/admin/dashboard.php");
exit;

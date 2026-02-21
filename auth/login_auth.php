<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// ─── LOGIN ───────────────────────────────────────────────

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
  $_SESSION['loginError'] = "All fields are required.";
  header("Location: /dashboard/login.php");
  exit;
}

$stmt = $conn->prepare("SELECT id, fullname, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    header("Location: /dashboard/student-request-page.php");
    exit;
  }
}

$_SESSION['loginError'] = "Invalid username or password.";
header("Location: /dashboard/login.php");
exit;

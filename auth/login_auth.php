<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// ─── LOGIN ───────────────────────────────────────────────

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
  $_SESSION['loginError'] = "All fields are required.";
  header("Location: " . url('/login'));
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
    switch ($user['role']) {
      case 'student':
        header("Location: " . url('/dashboard/student'));
        break;
      case 'instructor':
        header("Location: " . url('/dashboard/instructor'));
        break;
      case 'adviser':
        header("Location: " . url('/dashboard/adviser'));
        break;
      case 'technology_head':
        header("Location: " . url('/dashboard/technology-head'));
        break;
      case 'csd_council':
        header("Location: " . url('/dashboard/csd-council'));
        break;
      case 'admin':
        header("Location: " . url('/dashboard/admin'));
        break;
      default:
        header("Location: " . url('/login'));
    }
    exit;
  }
}

$_SESSION['loginError'] = "Invalid username or password.";
header("Location: " . url('/login'));
exit;

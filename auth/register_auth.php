<?php
require_once __DIR__ . '/../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/db.php';

$action = $_POST['action'] ?? '';

// ─── REGISTER ───────────────────────────────────────────────
if ($action === 'register') {
  $fullname = trim($_POST['fullname'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $role     = $_POST['role'] ?? 'student';

  // Basic validation
  if (empty($fullname) || empty($username) || empty($password)) {
    $_SESSION['registerError'] = "All fields are required.";
    header("Location: " . url('/register'));
    exit;
  }

  if (strlen($password) < 8) {
    $_SESSION['registerError'] = "Password must be at least 8 characters.";
    header("Location: " . url('/register'));
    exit;
  }

  // Check if username already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['registerError'] = "Username already taken.";
    $stmt->close();
    header("Location: " . url('/register'));
    exit;
  }
  $stmt->close();

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, role) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $fullname, $username, $hashed_password, $role);

  if ($stmt->execute()) {
    $_SESSION['registerSuccess'] = "";
    header("Location: " . url('/login'));
  } else {
    $_SESSION['registerError'] = "Something went wrong. Please try again.";
    header("Location: " . url('/register'));
  }
  $stmt->close();
  exit;
}

// If someone visits register_auth.php directly without POSTing
header("Location: " . url('/register'));
exit;
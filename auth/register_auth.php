<?php
session_start();
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
    header("Location: /dashboard/register.php");
    exit;
  }

  if (strlen($password) < 8) {
    $_SESSION['registerError'] = "Password must be at least 8 characters.";
    header("Location: /dashboard/register.php");
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
    header("Location: /dashboard/register.php");
    exit;
  }
  $stmt->close();

  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert new user into database
  $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, role) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $fullname, $username, $hashed_password, $role);

  if ($stmt->execute()) {
    $_SESSION['registerSuccess'] = "Registration successful! You can now log in.";
    header("Location: /dashboard/login.php");
  } else {
    $_SESSION['registerError'] = "Something went wrong. Please try again.";
    header("Location: /dashboard/register.php");
  }
  $stmt->close();
  exit;
}

// If someone visits auth.php directly
header("Location: ../index.php");
exit;

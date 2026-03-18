<?php
require_once __DIR__ . '/../middleware/auth_guard.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id            = $_SESSION['user_id'];
  $category           = $_POST['category'];
  $requesting_student = $_POST['requesting_student'];
  $section            = $_POST['section'] ?? null;
  $request_date       = $_POST['request_date'];
  $request_time       = $_POST['request_time'];
  $purpose            = $_POST['purpose'];
  $class_adviser      = $_POST['class_adviser'] ?? null;
  $technology_head    = $_POST['technology_head'] ?? null;
  $note               = $_POST['note'] ?? null;

  $stmt = $conn->prepare("
    INSERT INTO pass_slips 
      (user_id, category, requesting_student, section, request_date, request_time, purpose, class_adviser, technology_head, note)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");

  $stmt->bind_param(
    "isssssssss",
    $user_id,
    $category,
    $requesting_student,
    $section,
    $request_date,
    $request_time,
    $purpose,
    $class_adviser,
    $technology_head,
    $note
  );

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: /dashboard/student_request_page.php");
    exit;
  } else {
    // Store error and redirect back
    $_SESSION['slipError'] = "Failed to create pass slip. Please try again.";
    header("Location: /dashboard/student_request_page.php");
    exit;
  }
}

// Block direct access
header("Location: /dashboard/student_request_page.php");
exit;

<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$dashboardRoute = [
  'instructor'      => '/dashboard/instructor',
  'adviser'         => '/dashboard/adviser',
  'technology_head' => '/dashboard/technology-head',
  'csd_council'     => '/dashboard/csd-council',
  'student'         => '/dashboard/student', // add if students can delete their own slips
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id      = $_POST['id'];
  $role    = $_SESSION['role'];   
  $user_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("DELETE FROM pass_slips WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $user_id);

  if ($stmt->execute()) {
    if ($stmt->affected_rows === 0) {
      $_SESSION['slipError'] = "Slip not found or you don't have permission to delete it.";
      $stmt->close();
      $conn->close();
      header("Location: " . url($dashboardRoute[$role]));
    exit;
  }
    $stmt->close();
    $conn->close();
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  } else {
    $_SESSION['slipError'] = "Failed to delete. Please try again.";
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  }
}

// Block direct access
header("Location: " . url('/login'));
exit;
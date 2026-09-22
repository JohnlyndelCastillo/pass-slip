<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/notifications.php';

$dashboardRoute = [
  'instructor'      => '/dashboard/instructor',
  'adviser'         => '/dashboard/adviser',
  'technology_head' => '/dashboard/technology-head',
  'csd_council'     => '/dashboard/csd-council',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id          = $_POST['id'];
  $role        = $_SESSION['role'];
  $reviewed_by = $_SESSION['user_id'];
  $status_date = date('Y-m-d');

  $stmt = $conn->prepare("
    UPDATE pass_slips 
    SET approval_status = 'rejected', reviewed_by = ?, status_date = ?
    WHERE id = ?
  ");
  $stmt->bind_param("isi", $reviewed_by, $status_date, $id);

  if ($stmt->execute()) {
    // Notify the student
    $slipStmt = $conn->prepare("SELECT user_id FROM pass_slips WHERE id = ?");
    $slipStmt->bind_param("i", $id);
    $slipStmt->execute();
    $slip = $slipStmt->get_result()->fetch_assoc();
    createNotification($conn, $slip['user_id'], $id, "Your pass slip has been rejected.");

    $stmt->close();
    $conn->close();
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  } else {
    $_SESSION['approvalError'] = "Failed to reject slip.";
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  }
}

header("Location: " . url('/login'));
exit;
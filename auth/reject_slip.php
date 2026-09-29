<?php
require_once __DIR__ . '/../middleware/file_guard.php';
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
  $id          = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
  $role        = $_SESSION['role'] ?? '';
  $reviewed_by = $_SESSION['user_id'];
  $status_date = date('Y-m-d');

  $requiredStatus = [
    'instructor' => 'pending',
    'adviser' => 'teacher_approved',
    'technology_head' => 'adviser_approved',
    'csd_council' => 'techhead_approved',
  ];
  $assignmentColumn = [
    'instructor' => 'instructor',
    'adviser' => 'class_adviser',
    'technology_head' => 'technology_head',
    'csd_council' => null,
  ];
  if (!$id || !isset($requiredStatus[$role])) {
    http_response_code(400);
    exit('Invalid rejection request.');
  }

  $expected = $requiredStatus[$role];
  $column = $assignmentColumn[$role];
  $updateSql = "UPDATE pass_slips SET approval_status = 'rejected', reviewed_by = ?, status_date = ? WHERE id = ? AND approval_status = ?";
  if ($column !== null) $updateSql .= " AND `$column` = ?";
  $stmt = $conn->prepare($updateSql);
  if ($column !== null) $stmt->bind_param('isisi', $reviewed_by, $status_date, $id, $expected, $reviewed_by);
  else $stmt->bind_param('isis', $reviewed_by, $status_date, $id, $expected);

  if ($stmt->execute() && $stmt->affected_rows === 1) {
    // Notify the student
    $slipStmt = $conn->prepare("SELECT user_id FROM pass_slips WHERE id = ?");
    $slipStmt->bind_param("i", $id);
    $slipStmt->execute();
    $slip = $slipStmt->get_result()->fetch_assoc();
    if ($slip) createNotification($conn, $slip['user_id'], $id, "Your pass slip has been rejected.");

    $stmt->close();
    $conn->close();
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  } else {
    $_SESSION['approvalError'] = "You are not authorized to reject this slip at this stage.";
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  }
}

header("Location: " . url('/login'));
exit;

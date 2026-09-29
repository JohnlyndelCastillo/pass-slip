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

  // Determine next status based on current role
  $nextStatus = [
    'instructor'      => 'teacher_approved',
    'adviser'         => 'adviser_approved',
    'technology_head' => 'techhead_approved',
    'csd_council'     => 'fully_approved',
  ];

  // Determine which status this role is allowed to act on
  $requiredStatus = [
    'instructor'      => 'pending',
    'adviser'         => 'teacher_approved',
    'technology_head' => 'adviser_approved',
    'csd_council'     => 'techhead_approved',
  ];

  $assignmentColumn = [
    'instructor' => 'instructor',
    'adviser' => 'class_adviser',
    'technology_head' => 'technology_head',
    'csd_council' => null,
  ];

  if (!$id || !isset($requiredStatus[$role])) {
    http_response_code(400);
    exit('Invalid approval request.');
  }

  // Verify both the workflow stage and the approver selected on the slip.
  $column = $assignmentColumn[$role];
  $checkSql = 'SELECT id FROM pass_slips WHERE id = ? AND approval_status = ?';
  if ($column !== null) $checkSql .= " AND `$column` = ?";
  $checkStmt = $conn->prepare($checkSql);
  $expected = $requiredStatus[$role];
  if ($column !== null) $checkStmt->bind_param('isi', $id, $expected, $reviewed_by);
  else $checkStmt->bind_param('is', $id, $expected);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result()->fetch_assoc();

  if (!$checkResult) {
    $_SESSION['approvalError'] = "You are not authorized to approve this slip at this stage.";
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  }

  $status = $nextStatus[$role];

  $updateSql = 'UPDATE pass_slips SET approval_status = ?, reviewed_by = ?, status_date = ? WHERE id = ? AND approval_status = ?';
  if ($column !== null) $updateSql .= " AND `$column` = ?";
  $stmt = $conn->prepare($updateSql);
  if ($column !== null) $stmt->bind_param('sisisi', $status, $reviewed_by, $status_date, $id, $expected, $reviewed_by);
  else $stmt->bind_param('sisis', $status, $reviewed_by, $status_date, $id, $expected);

  if ($stmt->execute() && $stmt->affected_rows === 1) {

    // After successful update, notify next role
    $nextNotify = [
      'instructor'      => ['role' => 'adviser',          'message' => 'A pass slip has been approved by the instructor and requires your approval.'],
      'adviser'         => ['role' => 'technology_head',  'message' => 'A pass slip has been approved by the adviser and requires your approval.'],
      'technology_head' => ['role' => 'csd_council',      'message' => 'A pass slip has been approved by the technology head and requires your approval.'],
      'csd_council'     => ['role' => null,               'message' => null],
    ];

    if (in_array($role, ['instructor', 'adviser'], true)) {
      $nextApproverColumn = $role === 'instructor' ? 'class_adviser' : 'technology_head';
      $slipStmt = $conn->prepare("SELECT `$nextApproverColumn` AS next_approver FROM pass_slips WHERE id = ?");
      $slipStmt->bind_param('i', $id);
      $slipStmt->execute();
      $slip = $slipStmt->get_result()->fetch_assoc();
      if ($slip && ctype_digit((string) $slip['next_approver'])) {
        createNotification($conn, (int) $slip['next_approver'], $id, $nextNotify[$role]['message']);
      }
      $slipStmt->close();
    } elseif ($nextNotify[$role]['role']) {
      // CSD council has no per-user assignment field, so notify that role.
      notifyByRole($conn, $nextNotify[$role]['role'], $id, $nextNotify[$role]['message']);
    } else {
      // Fully approved — notify the student
      $slipStmt = $conn->prepare("SELECT user_id FROM pass_slips WHERE id = ?");
      $slipStmt->bind_param("i", $id);
      $slipStmt->execute();
      $slip = $slipStmt->get_result()->fetch_assoc();
      createNotification($conn, $slip['user_id'], $id, "Your pass slip has been fully approved!");
    }

    $stmt->close();
    $conn->close();
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  } else {
    $_SESSION['approvalError'] = "Failed to approve slip.";
    header("Location: " . url($dashboardRoute[$role]));
    exit;
  }
}

header("Location: " . url('/login'));
exit;

<?php
$allowedRoles = ['instructor', 'adviser', 'technology_head', 'csd_council'];
require_once __DIR__ . '/../middleware/auth_guard.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id          = $_POST['id'];
  $role        = $_SESSION['role'];
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

  // Verify the slip is at the correct stage for this role
  $checkStmt = $conn->prepare("SELECT approval_status FROM pass_slips WHERE id = ?");
  $checkStmt->bind_param("i", $id);
  $checkStmt->execute();
  $checkResult = $checkStmt->get_result()->fetch_assoc();

  if (!$checkResult || $checkResult['approval_status'] !== $requiredStatus[$role]) {
    $_SESSION['approvalError'] = "You are not authorized to approve this slip at this stage.";
    header("Location: /dashboard/{$role}/approval_page.php");
    exit;
  }

  $status = $nextStatus[$role];

  $stmt = $conn->prepare("
    UPDATE pass_slips 
    SET approval_status = ?, reviewed_by = ?, status_date = ?
    WHERE id = ?
  ");
  $stmt->bind_param("sisi", $status, $reviewed_by, $status_date, $id);

  if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: /dashboard/{$role}/approval_page.php");
    exit;
  } else {
    $_SESSION['approvalError'] = "Failed to approve slip.";
    header("Location: /dashboard/{$role}/approval_page.php");
    exit;
  }
}

header("Location: /dashboard/login.php");
exit;
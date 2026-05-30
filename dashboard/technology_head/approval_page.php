<?php
$role        = 'technology_head';
$allowedRoles = [$role];
$requiredStatus = 'adviser_approved';

require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../components/table_actions.php';
require_once __DIR__ . '/../../includes/notifications.php';

$unreadCount = getUnreadCount($conn, $_SESSION['user_id']);

$stmt = $conn->prepare("
  SELECT ps.*, 
    u.fullname AS student_name,
    a.fullname AS adviser_name,
    t.fullname AS techhead_name
  FROM pass_slips ps
  JOIN users u ON ps.user_id = u.id
  LEFT JOIN users a ON ps.class_adviser = a.id
  LEFT JOIN users t ON ps.technology_head = t.id
  WHERE ps.approval_status = ?
");
$stmt->bind_param("s", $requiredStatus);
$stmt->execute();
$result = $stmt->get_result();

include __DIR__ . '/../../components/approval/approval_page.php';
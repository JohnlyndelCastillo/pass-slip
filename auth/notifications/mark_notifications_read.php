<?php
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id'])) {
    // Mark single notification as read
    markOneAsRead($conn, $_POST['id'], $_SESSION['user_id']);
    echo json_encode(['success' => true]);
  } else {
    // Mark all as read
    markAllAsRead($conn, $_SESSION['user_id']);
    header("Location: " . $_SERVER['HTTP_REFERER']);
  }
}
exit;
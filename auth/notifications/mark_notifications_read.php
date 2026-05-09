<?php
require_once __DIR__ . '/../../middleware/auth_guard.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  markAllAsRead($conn, $_SESSION['user_id']);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
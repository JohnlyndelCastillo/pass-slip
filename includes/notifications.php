<?php
function createNotification($conn, $user_id, $slip_id, $message) {
  $stmt = $conn->prepare("
    INSERT INTO notifications (user_id, slip_id, message) 
    VALUES (?, ?, ?)
  ");
  $stmt->bind_param("iis", $user_id, $slip_id, $message);
  $stmt->execute();
}

function notifyByRole($conn, $role, $slip_id, $message) {
  $stmt = $conn->prepare("SELECT id FROM users WHERE role = ?");
  $stmt->bind_param("s", $role);
  $stmt->execute();
  $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

  foreach ($users as $user) {
    createNotification($conn, $user['id'], $slip_id, $message);
  }
}

function getUnreadCount($conn, $user_id) {
  $stmt = $conn->prepare("
    SELECT COUNT(*) AS count 
    FROM notifications 
    WHERE user_id = ? AND is_read = 0
  ");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_assoc()['count'];
}

function getNotifications($conn, $user_id) {
  $stmt = $conn->prepare("
    SELECT n.*, ps.purpose 
    FROM notifications n
    JOIN pass_slips ps ON n.slip_id = ps.id
    WHERE n.user_id = ?
    ORDER BY n.created_at DESC
    LIMIT 10
  ");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function markAllAsRead($conn, $user_id) {
  $stmt = $conn->prepare("
    UPDATE notifications SET is_read = 1 
    WHERE user_id = ?
  ");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
}
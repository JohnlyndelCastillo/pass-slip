<?php
$unreadCount   = getUnreadCount($conn, $_SESSION['user_id']);
$notifications = getNotifications($conn, $_SESSION['user_id']);
?>

<div class="notification-dropdown" id="notificationDropdown">
  <div class="notification-header">
    <span>Notifications</span>
    <?php if ($unreadCount > 0): ?>
      <form action="/auth/notifications/mark_notifications_read.php" method="POST" style="margin:0">
        <button type="submit" class="mark-read-btn">Mark all as read</button>
      </form>
    <?php endif; ?>
  </div>
  <div class="notification-list">
    <?php if (empty($notifications)): ?>
      <div class="notification-empty">No notifications yet.</div>
    <?php else: ?>
      <?php foreach ($notifications as $notif): ?>
        <div class="notification-item <?= $notif['is_read'] ? '' : 'unread' ?>">
          <p class="notification-message"><?= htmlspecialchars($notif['message']) ?></p>
          <span class="notification-time"><?= date('M d, Y h:i A', strtotime($notif['created_at'])) ?></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
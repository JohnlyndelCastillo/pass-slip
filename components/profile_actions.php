<?php
// Fetch the logged-in user's details once, server-side.
// Pages that include this component already have $conn (from includes/db.php)
// and $_SESSION['user_id'] (from middleware/auth_guard.php).
$profileUser = null;
if (!empty($_SESSION['user_id']) && isset($conn)) {
    $stmt = $conn->prepare("SELECT fullname, username, role, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $profileUser = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<div class="profile-dropdown" id="profileDropdown">
  <?php if ($profileUser): ?>
    <div class="profile-dropdown-title">Profile</div>

    <div class="profile-dropdown-user">
      <div class="profile-dropdown-avatar">
        <i class="fa-solid fa-user"></i>
      </div>
      <div class="profile-dropdown-info">
        <p class="profile-dropdown-fullname"><?= htmlspecialchars($profileUser['fullname']) ?></p>
        <p class="profile-dropdown-username">@<?= htmlspecialchars($profileUser['username']) ?></p>
        <span class="profile-dropdown-role"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $profileUser['role']))) ?></span>
      </div>
    </div>

    <div class="profile-dropdown-detail">
      <span class="profile-dropdown-detail-label">Member Since</span>
      <span class="profile-dropdown-detail-value"><?= date('M d, Y', strtotime($profileUser['created_at'])) ?></span>
    </div>
  <?php endif; ?>

  <form action="/auth/logout_auth.php" method="POST">
    <button type="submit" class="profile-dropdown-logout">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </button>
  </form>
</div>
<?php
/**
 * Auth helpers used by index.php.
 *
 * requireLogin() matches your original middleware/auth_guard.php exactly
 * (session_start + check for $_SESSION['user_id']), just as a function
 * instead of a script.
 *
 * requireRole() is NEW — your previous auth_guard.php never checked role,
 * even though pages set $allowedRoles. This is what actually enforces it
 * now, via the routes.php 'roles' entries.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Returns the logged-in user's id, or null if nobody is logged in. */
function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

/** Redirects to /login and stops execution if nobody is logged in. */
function requireLogin(): int {
    $id = currentUserId();
    if ($id === null) {
        header('Location: /pass-slip/login');
        exit;
    }
    return $id;
}

/** Requires login AND that the user's role is in $roles. */
function requireRole(array $roles): int {
    global $conn; // set up by includes/db.php, required before this runs

    $id = requireLogin();

    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row || !in_array($row['role'], $roles, true)) {
        http_response_code(403);
        echo "403 — you don't have access to this page.";
        exit;
    }

    return $id;
}
<?php
/**
 * Route table.
 *
 * Key: "METHOD /path" (method in caps, no trailing slash on the path)
 * 'file'  -> path to the PHP file to run, relative to the project root
 * 'roles' -> allowed roles, or omitted/empty for "any logged-in user".
 *            Use 'public' => true for pages that don't require login at all.
 *
 * Adjust the file paths and role names to match your actual auth data.
 */
return [

    // ---- Public routes (no login required) ----
    'GET /login'          => ['file' => 'dashboard/login.php', 'public' => true],
    'GET /register'       => ['file' => 'dashboard/register.php', 'public' => true],
    'POST /auth/login'    => ['file' => 'auth/login_auth.php', 'public' => true],
    'POST /auth/register' => ['file' => 'auth/register_auth.php', 'public' => true],
    'POST /auth/logout'   => ['file' => 'auth/logout_auth.php'], // logged-in users only

    // ---- Dashboards (one route per role) ----
    'GET /dashboard/admin'            => ['file' => 'dashboard/admin/dashboard.php', 'roles' => ['admin']],
    'GET /dashboard/student'          => ['file' => 'dashboard/student/request_page.php', 'roles' => ['student']],
    'GET /dashboard/adviser'          => ['file' => 'dashboard/adviser/approval_page.php', 'roles' => ['adviser']],
    'GET /dashboard/instructor'       => ['file' => 'dashboard/instructor/approval_page.php', 'roles' => ['instructor']],
    'GET /dashboard/csd-council'      => ['file' => 'dashboard/csd_council/approval_page.php', 'roles' => ['csd_council']],
    'GET /dashboard/technology-head'  => ['file' => 'dashboard/technology_head/approval_page.php', 'roles' => ['technology_head']],

    // ---- Pass slip actions ----
    'POST /slips'                => ['file' => 'auth/create_slip.php', 'roles' => ['student']],
    'POST /slips/approve'        => ['file' => 'auth/approve_slip.php', 'roles' => ['adviser', 'instructor', 'csd_council', 'technology_head']],
    'POST /slips/reject'         => ['file' => 'auth/reject_slip.php', 'roles' => ['adviser', 'instructor', 'csd_council', 'technology_head']],
    'POST /slips/delete'         => ['file' => 'auth/delete_slip.php'],

    // ---- Admin: user management ----
    'POST /admin/users'          => ['file' => 'auth/admin/create_user.php', 'roles' => ['admin']],
    'POST /admin/users/edit'     => ['file' => 'auth/admin/edit_user.php', 'roles' => ['admin']],
    'POST /admin/users/delete'   => ['file' => 'auth/admin/delete_user.php', 'roles' => ['admin']],

    // ---- Notifications ----
    'POST /notifications/read'   => ['file' => 'auth/notifications/mark_notifications_read.php'],

];
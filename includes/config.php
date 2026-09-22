<?php
/**
 * Shared app config.
 *
 * Include this FIRST (before db.php, auth_guard.php, or any redirect
 * logic) in index.php and in any file that builds a URL or a
 * Location: header directly (e.g. auth/login_auth.php).
 *
 * Change BASE_PATH in ONE place if the app ever moves to a different
 * subdirectory, or back to serving from the root ('').
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', '/pass-slip'); // no trailing slash; '' for root
}

/**
 * Builds an absolute URL path under BASE_PATH.
 * url('/login')            -> /pass-slip/login
 * url('/dashboard/student') -> /pass-slip/dashboard/student
 */
function url(string $path): string {
    return BASE_PATH . '/' . ltrim($path, '/');
}
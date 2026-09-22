<?php
/**
 * Front controller.
 *
 * Every request that isn't a real file or folder is sent here by the
 * Apache rewrite rule (see docker/apache-hardening.conf notes below).
 * This file looks the request up in routes.php, checks auth, then
 * hands off to the matching page.
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/middleware/auth_guard.php';

$routes = require __DIR__ . '/routes.php';

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = '/' . trim($path, '/');
if (str_starts_with($path, BASE_PATH)) {
    $path = substr($path, strlen(BASE_PATH)) ?: '/';
}
if ($path === '/') {
    $path = '/login';
}


$key = "$method $path";

if (!isset($routes[$key])) {
    http_response_code(404);
    echo "404 — page not found: " . htmlspecialchars($key);
    exit;
}

$route = $routes[$key];

// --- Auth check ---
if (empty($route['public'])) {
    if (empty($route['roles'])) {
        requireLogin();                 // any logged-in user
    } else {
        requireRole($route['roles']);   // logged in AND has one of these roles
    }
}

// --- Run the target file ---
$target = __DIR__ . '/' . ltrim($route['file'], '/');
if (!is_file($target)) {
    http_response_code(500);
    echo "Route points to a missing file: " . htmlspecialchars($route['file']);
    exit;
}

require $target;
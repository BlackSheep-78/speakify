<?php
// file: /speakify/public/index.php
// Load app-wide config, autoloaders, error handling, etc.
require_once __DIR__ . '/../init.php';

Logger::info(
    "Incoming request: " . json_encode([
        'URI' => $_SERVER['REQUEST_URI'] ?? '',
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? '',
        'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? '',
        'PHP_SELF' => $_SERVER['PHP_SELF'] ?? '',
        'QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? '',
        'GET' => $_GET,
    ]),
    __FILE__,
    __LINE__
);

// Define allowed views
$allowedViews = [
    'dashboard',
    'playback',
    'login-profile',
    'register',
    'offline-mode',
    'playlist-editor',
    'playlist-library',
    'schema-editor',
    'settings',
    'smart-lists',
    'achievements',
    'admin'
];

// Determine the view
$page = $_GET['page'] ?? basename($_SERVER['REQUEST_URI']) ?: 'dashboard';
$page = basename($page);

// Fallback to 404 if not allowed
if (!in_array($page, $allowedViews)) {
    $page = '404';
}

// Render view
$viewPath = __DIR__ . "/views/{$page}.php";
if (file_exists($viewPath)) {
    include __DIR__ . "/views/header.php";
    include $viewPath;
    include __DIR__ . "/views/footer.php";
} else {
    http_response_code(404);
    echo "<h1>404 - View not found</h1>";
}

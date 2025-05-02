<?php
// file: /speakify/public/index.php
// Load app-wide config, autoloaders, error handling, etc.
require_once __DIR__ . '/../init.php';

$database = Database::init();
$page     = Input::get('page', 'string', 'dashboard');
$page     = basename($page);

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



// Fallback to 404 if not allowed
if (!in_array($page, $allowedViews)) 
{
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

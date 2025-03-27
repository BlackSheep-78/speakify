<?php
// file: speakify/backend/init.php
// Purpose: Bootstraps the environment, session, and loads config

// Load config array
$config = require_once __DIR__ . '/config.php';

// Optional: Set timezone from config or default
date_default_timezone_set('Europe/London');

// Register custom session handler
require_once __DIR__ . '/classes/SessionManager.php';

$handler = new SessionManager($config); // you may need DB credentials from $config
session_set_save_handler($handler, true);

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize session data if missing
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = uniqid('sess_', true);
    $_SESSION['created_at'] = time();
    $_SESSION['logged_in'] = false;
}

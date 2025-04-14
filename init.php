<?php

// =============================================================================
// 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
// =============================================================================
// File: speakify/init.php
// Project: Speakify
//
// Description:
// Initializes the Speakify backend environment and request lifecycle.
//
// 🧾 Responsibilities:
//  1. Define BASEPATH constant (project root)
//  2. Load config via backend/init/config.php
//  3. Expose global $CREDENTIALS
//  4. Initialize PDO connection and set $GLOBALS['pdo']
//  5. Initialize Logger (with error/exception handlers)
//  6. Load and validate user session (SessionManager)
//  7. Register class autoloader for backend/classes/
// =============================================================================

// ✅ Define BASEPATH
if (!defined('BASEPATH')) {
  define('BASEPATH', realpath(__DIR__));
}

// ✅ Load core backend components
require BASEPATH . '/backend/init/config.php';
require BASEPATH . '/backend/classes/Logger.php';
require BASEPATH . '/backend/classes/Database.php';


// ✅ Create PDO connection and expose globally
try {
  $db = $GLOBALS['CREDENTIALS']['db'];
  $pdo = new PDO(
    "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4",
    $db['user'],
    $db['pass'],
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
  $GLOBALS['pdo'] = $pdo;

  // Re-init logger with full DB access
  Logger::init($pdo);
} catch (PDOException $e) {
  Logger::error("❌ PDO connection failed: " . $e->getMessage(), __FILE__, __LINE__);
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
  exit;
}

// ✅ Session Manager
require_once BASEPATH . '/backend/classes/SessionManager.php';

$public_actions = ['register_user', 'create_session', 'validate_session', 'login'];
$current_action = $_GET['action'] ?? null;

if (!$current_action) return;

$token = $_GET['token'] ?? '';
$session = SessionManager::validateOrCreate($token);
$_GET['token'] = $token;
$GLOBALS['auth_user_id'] = $session['user_id'] ?? null;

// ✅ Autoloader for backend/classes/
spl_autoload_register(function ($class) {
  $file = BASEPATH . '/backend/classes/' . $class . '.php';
  if (file_exists($file)) {
    require_once $file;
  }
});

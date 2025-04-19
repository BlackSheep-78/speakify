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
// 📜 Responsibilities:
//  1. Define BASEPATH constant (project root)
//  2. Load config via backend/core/ConfigLoader.php
//  3. Expose global $CREDENTIALS
//  4. Initialize PDO connection and set $GLOBALS['pdo']
//  5. Initialize Logger (with error/exception handlers)
//  6. Load and validate user session (SessionManager)
//  7. Register class autoloader for backend/classes/
// =============================================================================

// [1] Define BASEPATH
define('BASEPATH', realpath(__DIR__));

// [2] Composer autoloader check
$autoload = BASEPATH . '/vendor/autoload.php';
if (!file_exists($autoload)) {
  http_response_code(500);
  echo json_encode([
    'error' => '🚫 Missing Composer dependencies',
    'hint' => 'Run `composer install` from project root to restore vendor folder.'
  ]);
  exit;
}
require_once $autoload;

// [3] Autoloader for backend/classes/
spl_autoload_register(function ($class) {
  $paths = [
    BASEPATH . '/backend/classes/core/',
    BASEPATH . '/backend/classes/logic/',
    BASEPATH . '/backend/classes/models/',
    BASEPATH . '/backend/classes/services/',
    BASEPATH . '/backend/classes/auth/'
  ];

  foreach ($paths as $path) {
    $file = $path . $class . '.php';
    if (file_exists($file)) {
      require_once $file;
      return;
    }
  }
});

// [4] Load config using ConfigLoader
$config = ConfigLoader::load();

// [5] Stop if config is still a template
if (!empty($config['template'])) {
  $msg = <<<HTML
  <h2>⚠️ Speakify Configuration Required</h2>
  <p>Your <code>config.json</code> was just created from the template.</p>
  <p>Please update it with your database and API credentials, then set <code>"template": false</code>.</p>
  HTML;

  if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'text/html')) {
    echo $msg;
  } else {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
      'error' => 'Configuration template detected',
      'hint' => 'Update config.json and set "template": false'
    ]);
  }
  exit;
}

// [6] Create database instance
$database = Database::init(); 

// [8] Session Manager
$public_actions = PublicActions::get();
$current_action = $_GET['action'] ?? null;

if (basename($_SERVER['SCRIPT_NAME']) === 'index.php' && strpos($_SERVER['REQUEST_URI'], '/api/') !== false) 
{
  $current_action = $_GET['action'] ?? null;

  if (!$current_action) {
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'error' => 'Api action not allowed',
      'hint' => 'Add this action to public_actions @init.php'
    ]);
    exit;
  }

  if (!in_array($current_action, $public_actions)) {
    $token = $_GET['token'] ?? ($_COOKIE['speakify_token'] ?? '');
    $session = strlen($token) >= 64
      ? SessionManager::validate($token)
      : SessionManager::create();

    $token = $session['token'] ?? '';
    $_GET['token'] = $token;
    $GLOBALS['auth_user_id'] = $session['user_id'] ?? null;

    // Optional: Persist token in cookie for frontend
    setcookie('speakify_token', $token, [
      'expires' => time() + 86400 * 7,
      'path' => '/',
      'secure' => false,
      'httponly' => false,
      'samesite' => 'Lax'
    ]);
  } else {
    $GLOBALS['auth_user_id'] = null;
  }
}

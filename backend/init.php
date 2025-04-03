<?php

/**
 * =============================================================================
 * 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
 * =============================================================================
 * File: speakify/backend/init.php
 * Project: Speakify
 *
 * Description:
 * Loads and initializes:
 * - Full configuration from config.json
 * - Global $CREDENTIALS for use by app components
 * - PDO database connection (MySQL)
 * - Error and exception handlers
 * - Conditionally loads SessionManager and enforces session validation.
 * - Automatically creates a new anonymous session if none is valid.
 * =============================================================================
 */

// ✅ Define BASEPATH as the project root: /speakify
if (!defined('BASEPATH')) 
{
  define('BASEPATH', realpath(__DIR__ . '/..'));
}

// ✅ Load core classes and logger
require_once BASEPATH . '/config.php';
require_once BASEPATH . '/backend/classes/Database.php';
require_once __DIR__ . '/utils/logger.php';

// ✅ Global error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext = []) {
  $level = match ($errno) {
    E_ERROR, E_USER_ERROR => 'ERROR',
    E_WARNING, E_USER_WARNING => 'WARNING',
    E_NOTICE, E_USER_NOTICE => 'NOTICE',
    default => 'LOG',
  };
  log_error_to_db($level, $errstr, $errfile, $errline, $errcontext);
  return false;
});

set_exception_handler(function ($exception) {
  log_error_to_db('EXCEPTION', $exception->getMessage(), $exception->getFile(), $exception->getLine());
});

// ✅ Load configuration from config.json and expose it globally
$configPath = BASEPATH . '/config.json';
if (!file_exists($configPath)) {
  error_log("❌ config.json not found at $configPath");
  http_response_code(500);
  echo json_encode(['error' => 'Missing config.json']);
  exit;
}

$configContent = file_get_contents($configPath);
$config = json_decode($configContent, true);

if (!$config || json_last_error() !== JSON_ERROR_NONE) {
  error_log("❌ Failed to parse config.json: " . json_last_error_msg());
  http_response_code(500);
  echo json_encode(['error' => 'Invalid JSON in config.json']);
  exit;
}

// ✅ Validate expected keys
if (!isset($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['name'])) {
  error_log("❌ config.json is missing one or more required DB keys");
  http_response_code(500);
  echo json_encode(['error' => 'Incomplete DB credentials in config']);
  exit;
}

$GLOBALS['CREDENTIALS'] = $config;

// ✅ Create PDO connection (optional future use)
try {
  $pdo = new PDO(
    "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset=utf8mb4",
    $config['db']['user'],
    $config['db']['pass'],
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
  $GLOBALS['pdo'] = $pdo;
} catch (PDOException $e) {
  error_log("❌ PDO connection failed: " . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
  exit;
}

// ✅ Session Manager logic
require_once __DIR__ . '/classes/SessionManager.php';

$public_actions = ['register_user', 'create_session', 'validate_session', 'login'];
$current_action = $_GET['action'] ?? null;

if (!$current_action) return;

$token = $_GET['token'] ?? '';
$session = SessionManager::validateOrCreate($token);
$_GET['token'] = $token;
$GLOBALS['auth_user_id'] = $session['user_id'] ?? null;

// ✅ Autoload app-level classes
spl_autoload_register(function ($class) {
  $file = BASEPATH . '/backend/classes/' . $class . '.php';
  if (file_exists($file)) {
    require_once $file;
  }
});

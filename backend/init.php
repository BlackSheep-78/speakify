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
 * - PDO database connection (MySQL)
 * - Conditionally loads SessionManager and enforces session validation.
 * - Automatically creates a new anonymous session if none is valid.
 * =============================================================================
 */

error_log("📥 ENTERING #1 init.php");

require_once __DIR__ . '/classes/Database.php';

// ✅ Load configuration from config.json
$configPath = __DIR__ . '/../config.json';
if (!file_exists($configPath)) {
  http_response_code(500);
  echo json_encode(['error' => 'Missing config.json']);
  exit;
}

$configContent = file_get_contents($configPath);
$config = json_decode($configContent, true);

if (!isset($config['db'])) {
  http_response_code(500);
  echo json_encode(['error' => 'Invalid database configuration']);
  exit;
}

// ✅ Create PDO connection
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
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
  exit;
}

// ✅ Load SessionManager and autoloader
require_once __DIR__ . '/classes/SessionManager.php';

$public_actions = ['register_user', 'create_session', 'validate_session', 'login'];
$current_action = $_GET['action'] ?? null;

// ✅ If no action (e.g., frontend index.php), skip session check
if (!$current_action) return;

// ✅ Always ensure a valid session (auto-create if needed)
$token = $_GET['token'] ?? '';
$session = SessionManager::validateOrCreate($token);
$_GET['token'] = $token; // update in case it was regenerated
$GLOBALS['auth_user_id'] = $session['user_id'] ?? null;

spl_autoload_register(function ($class) {
  $file = __DIR__ . '/../classes/' . $class . '.php';
  if (file_exists($file)) {
    require_once $file;
  }
});

error_log("📥 EXITING init.php");

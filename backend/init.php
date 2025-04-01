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
 * - Conditionally loads SessionManager and enforces session validation
 * =============================================================================
 */

 error_log("📥 ENTERING #1 init.php");

// Debug log
file_put_contents(__DIR__ . '/token-check.log', "ACTION: " . ($_GET['action'] ?? 'none') . PHP_EOL, FILE_APPEND);

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
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
  exit;
}

// ✅ Conditionally load session manager only if needed
require_once __DIR__ . '/classes/SessionManager.php';

$public_actions = ['register_user', 'create_session'];
$current_action = $_GET['action'] ?? null;

// ✅ If no action (e.g., frontend index.php), skip session check
if (!$current_action) return;

if (!in_array($current_action, $public_actions)) {
  $token = $_GET['token'] ?? '';

  $session = SessionManager::validate($token);
  if (!$token || !$session) {
    http_response_code(401);
    echo json_encode(['error' => 'Missing or invalid session token']);
    exit;
  }

  // Optional: set user ID globally
  $GLOBALS['auth_user_id'] = $session['user_id'] ?? null;
}


spl_autoload_register(function ($class) {
  $file = __DIR__ . '/../classes/' . $class . '.php';
  if (file_exists($file)) {
    require_once $file;
  }
});

error_log("📥 EXITING init.php");

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

 file_put_contents(__DIR__ . '/token-check.log', "ACTION: " . ($_GET['action'] ?? 'none') . PHP_EOL, FILE_APPEND);




$pdo = new PDO(
  "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset=utf8mb4",
  $config['db']['user'],
  $config['db']['pass'],
  [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]
);

// ✅ Conditionally load session manager only if needed
$public_actions = ['register_user', 'create_session'];
$current_action = $_GET['action'] ?? null;

if (!in_array($current_action, $public_actions)) {
  require_once __DIR__ . '/classes/SessionManager.php';

  $token = $_GET['token'] ?? '';
  if (!$token || !SessionManager::validateToken($token)) {
    http_response_code(401);
    echo json_encode(['error' => 'Missing session token']);
    exit;
  }
} else {
  require_once __DIR__ . '/classes/SessionManager.php';
}
<?php
// File: backend/controllers/validate_session.php

require_once __DIR__ . '/../init.php'; // defines BASE_PATH
require_once BASE_PATH . 'classes/SessionManager.php';

header('Content-Type: application/json');

$token = $_GET['token'] ?? $_POST['token'] ?? null;

$session = SessionManager::validate($token);

if (!$session) {
  // Return a clear failure response
  echo json_encode([
    'success' => false,
    'error' => 'Invalid or expired session',
    'token' => null
  ]);
  exit;
}

// Return a success response with session info
echo json_encode([
  'success' => true,
  'user_id' => $session['user_id'] ?? null,
  'token' => $session['token'],
  'last_activity' => $session['last_activity'],
  'expires_at' => $session['expires_at']
]);

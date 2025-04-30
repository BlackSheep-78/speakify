<?php
// =============================================================================
// Project: Speakify
// File: /backend/controllers/logout.php
// Description: Logs the user out of their session by clearing user_id
// =============================================================================

header('Content-Type: application/json');

$token = Input::get('token', '');  // Sanitized input using Input class

global $database;
$sessionManager = new SessionManager(['db' => $database]);

$sessionManager->logout($token);

echo json_encode(['success' => true, 'message' => 'Logged out']);

<?php
// =============================================================================
// Project: Speakify
// File: /backend/controllers/logout.php
// Description: Logs the user out of their session by clearing user_id
// =============================================================================

header('Content-Type: application/json');

global $database;

$token          = Input::get('token','token','');  // Sanitized input using Input class
$sessionManager = new SessionManager(['db' => $database]);

$sessionManager->logout($token);

output(['success' => true, 'message' => 'Logged out']);

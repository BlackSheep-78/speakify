<?php

/**
 * =============================================================================
 * 📌 Controller: validate_session.php
 * =============================================================================
 *
 * This file is a controller responsible for handling the session validation logic.
 * It should contain as little business logic as possible. The goal is to interact
 * with the `SessionManager` and return a response based on the session's validity.
 *
 * ✅ The controller:
 * - Validates the session based on the token passed.
 * - Returns the session information if valid.
 * - Returns an error message if the session is invalid or expired.
 *
 * ⚠️ Business logic should ideally be handled by the `SessionManager` or service classes.
 * =============================================================================
 */

require_once BASEPATH . '/backend/classes/SessionManager.php';
require_once BASEPATH . '/backend/classes/Database.php';  // Assuming you want to fetch user info from the database

header('Content-Type: application/json');

// Get token from the request (GET or POST)
$token = $_GET['token'] ?? $_POST['token'] ?? null;

// Validate session based on the token
$session = SessionManager::validate($token);

// If session is invalid, return error
if (!$session) {
  echo json_encode([
    'success' => false,
    'error' => 'Invalid or expired session',
    'token' => null
  ]);
  exit;  // Ensure no further processing
}

// If the session is valid and contains a user_id, fetch the user's name
$name = null;
if (isset($session['user_id'])) {
    // The session is authenticated, fetch user data
    $userStmt = $pdo->prepare("SELECT name FROM users WHERE id = :user_id LIMIT 1");
    $userStmt->execute(['user_id' => $session['user_id']]);
    $user = $userStmt->fetch();

    if ($user) {
        $name = $user['name'];  // Get the name of the authenticated user
    }
}

// Return the session information along with user name (if available)
echo json_encode([
  'success' => true,
  'name' => $name,  // If it's an anonymous session, this will be null
  'token' => $session['token'],
  'last_activity' => $session['last_activity'],
  'expires_at' => $session['expires_at']
]);

exit; // ✅ Ensure we stop execution after sending the response

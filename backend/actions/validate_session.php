<?php
/*
  ============================================================================
  📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
  ============================================================================
  This header defines the expected behavior of the Speakify session validation
  logic. These rules must be enforced consistently across all session validation
  and management endpoints.
  ============================================================================
  
  ============================================================================
  validate_session.php – Speakify Session Validation Logic
  ============================================================================

  🎯 Purpose:
    Validates the session token passed from the frontend and checks if the session
    is valid and not expired. Optionally updates the last activity time (touches the session).

  ✅ Session Validation Rules:
  1. The session token is required to perform validation.
  2. The token must exist in the database and not be expired.
  3. On valid sessions, `last_activity` will be updated if needed.
  4. Response is always in JSON format, either success or error.

  ============================================================================
  File: speakify/backend/actions/validate_session.php
  Description: Validates if a session token is valid and not expired.
  ============================================================================
*/

// Define base path to the backend folder
require_once BASE_PATH . '/init.php'; // loads SessionManager and DB connection

header('Content-Type: application/json');

// Retrieve token
$token = $_GET['token'] ?? '';

if (!$token) {
    echo json_encode(['error' => 'Missing token']);
    exit;
}

// Validate token using SessionManager
if (!SessionManager::validate($token)) {
    echo json_encode(['error' => 'Invalid or expired session']);
    exit;
}

// Optional: Update last_activity if needed (touch)
SessionManager::touch($token);

// Return success
echo json_encode(['success' => true, 'token' => $token]);
exit;
?>


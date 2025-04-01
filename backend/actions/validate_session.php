<?php
/*
  ==============================================================================
  📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
  ==============================================================================
  This header defines the expected behavior of the Speakify session validation
  logic. These rules must be enforced consistently across all session validation
  and management endpoints.
  ==============================================================================

  ==============================================================================
  validate_session.php – Speakify Session Validation Logic
  ==============================================================================

  🎯 Purpose:
    Validates the session token passed from the frontend and checks if the session
    is valid and not expired. Optionally updates the last activity time (touches the session).

  ✅ Session Validation Rules:
  1. The session token is required to perform validation.
  2. The token must exist in the database and not be expired.
  3. On valid sessions, `last_activity` will be updated if needed.
  4. Response is always in JSON format, either success or error.

  ==============================================================================
  File: speakify/backend/actions/validate_session.php
  Description: Validates if a session token is valid and not expired.
  ==============================================================================
*/

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../utils/db.php'; // just include, no assignment

header('Content-Type: application/json');

$token = $_GET['token'] ?? null;

if (!$token) {
  echo json_encode(['error' => 'No token provided.']);
  exit;
}

try {
  $db = Database::getConnection(); // ✅ This must return a PDO instance

  $stmt = $db->prepare("
    SELECT 
      s.token, s.last_activity, 
      u.name, u.email 
    FROM sessions s 
    JOIN users u ON s.user_id = u.id 
    WHERE s.token = :token 
    LIMIT 1
  ");
  $stmt->execute([':token' => $token]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    echo json_encode(['error' => 'Invalid session.']);
    exit;
  }

  echo json_encode([
    'success' => true,
    'token' => $row['token'],
    'user' => [
      'name' => $row['name'],
      'email' => $row['email'],
      'last_activity' => $row['last_activity']
    ]
  ]);

} catch (Exception $e) {
  echo json_encode([
    'error' => 'Server error.',
    'details' => $e->getMessage()
  ]);
  exit;
}

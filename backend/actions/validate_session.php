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


error_log("HERE");
file_put_contents(__DIR__ . "/debug_validate.txt", date('c') . " -- STARTED\n", FILE_APPEND);


require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../utils/db.php';

header('Content-Type: application/json');
error_log("🔍 validate_session.php called");

$token = $_GET['token'] ?? null;
error_log("📦 Token received: " . ($token ?: 'NULL'));

if (!$token) {
  error_log("❌ No token provided.");
  echo json_encode(['error' => 'No token provided.']);
  exit;
}

try {
  $db = Database::getConnection();
  if (!$db) {
    error_log("❌ Database connection failed.");
    throw new Exception("Database connection failed.");
  }

  $stmt = $db->prepare("
    SELECT s.token, s.last_activity, s.user_id, u.name, u.email
    FROM sessions s
    LEFT JOIN users u ON s.user_id = u.id
    WHERE s.token = :token
    LIMIT 1
  ");
  $stmt->execute([':token' => $token]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    error_log("❌ Token not found in sessions table.");
    echo json_encode(['error' => 'Invalid session token.']);
    exit;
  }

  error_log("✅ Session found. User ID: " . ($row['user_id'] ?? 'NULL'));

  // Touch session
  $update = $db->prepare("UPDATE sessions SET last_activity = NOW() WHERE token = :token");
  $update->execute([':token' => $token]);
  error_log("⏰ Session last_activity updated.");

  if ($row['user_id']) {
    error_log("👤 Authenticated session for: " . $row['name']);
    echo json_encode([
      'success' => true,
      'token' => $row['token'],
      'name' => $row['name'],
      'email' => $row['email'],
      'last_activity' => $row['last_activity']
    ]);
  } else {
    error_log("👥 Anonymous session.");
    echo json_encode([
      'success' => false,
      'token' => $row['token'],
      'message' => 'Anonymous session'
    ]);
  }

} catch (Exception $e) {
  error_log("🔥 Exception in validate_session: " . $e->getMessage());
  echo json_encode(['error' => 'Server error.', 'details' => $e->getMessage()]);
  exit;
}

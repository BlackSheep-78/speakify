<?php
/*
  ==============================================================================
  📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
  ==============================================================================
  This header defines the expected behavior of the Speakify session creation
  logic. These rules must be enforced consistently across session management.

  ==============================================================================
  create_session.php – Speakify Anonymous Session Creation
  ==============================================================================

  🎯 Purpose:
    Creates an anonymous session in the database and returns a secure token.
    This session is later upgradeable to a logged-in user session.

  ✅ Session Creation Rules:
  1. A 64-character secure token is generated.
  2. The token is inserted into the database with a timestamp.
  3. The session starts as anonymous (no user_id).
  4. Response is always in JSON format.

  ==============================================================================
  File: speakify/backend/actions/create_session.php
  Description: Creates an anonymous session for the frontend.
  ==============================================================================
*/

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../utils/db.php';

header('Content-Type: application/json');
error_log("📥 create_session.php called");

try {
  $token = bin2hex(random_bytes(32)); // Secure token
  error_log("🔐 Generated token: $token");

  $db = Database::getConnection();
  if (!$db) {
    error_log("❌ Database connection failed (null returned)");
    throw new Exception("Database connection not established.");
  }

  $stmt = $db->prepare("INSERT INTO sessions (token, created_at, last_activity) VALUES (:token, NOW(), NOW())");
  $stmt->execute([':token' => $token]);

  error_log("✅ Session inserted into database successfully.");

  echo json_encode([
    'success' => true,
    'token' => $token
  ]);
} catch (Exception $e) {
  error_log("❌ Error creating session: " . $e->getMessage());
  echo json_encode([
    'error' => 'Could not create session.',
    'details' => $e->getMessage()
  ]);
  exit;
}

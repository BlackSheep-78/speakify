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

header('Content-Type: application/json'); // [1]
Logger::log("📥 create_session.php called", __FILE__, __LINE__); // [2]

try {
  $token   = bin2hex(random_bytes(32)); // [3]
  $now     = date('Y-m-d H:i:s'); // [4]
  $expires = date('Y-m-d H:i:s', strtotime('+8 hours')); // [5]

  Logger::log("🔐 Generated token: $token", __FILE__, __LINE__); // [6]

  Database::init()
    ->file('/session/insert_session.sql') // [7]
    ->replace(':TOKEN', $token, 's')
    ->replace(':NOW', $now, 's')
    ->replace(':EXPIRES', $expires, 's')
    ->result(); // [8]

  Logger::log("✅ Session inserted into database successfully.", __FILE__, __LINE__); // [9]

  echo json_encode([ // [10]
    'success' => true,
    'token' => $token
  ]);
} catch (Exception $e) {
  Logger::log("❌ Error creating session: " . $e->getMessage()); // [11]
  echo json_encode([ // [12]
    'error' => 'Could not create session.',
    'details' => $e->getMessage()
  ]);
  exit; // [13]
}

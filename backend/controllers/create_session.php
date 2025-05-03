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

header('Content-Type: application/json');
Logger::info("📥 create_session.php called");

try {
  $session = SessionManager::create();
  Logger::info("✅ Session inserted into database successfully.");

  output([
    'success' => true,
    'token' => $session['token']
  ]);
} catch (Exception $e) {
  Logger::info("❌ Error creating session: " . $e->getMessage());

  output([
    'error' => 'Could not create session.',
    'details' => $e->getMessage()
  ]);
  exit;
}

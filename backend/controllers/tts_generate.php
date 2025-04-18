<?php
// =============================================================================
// File: backend/controllers/tts_generate.php
// Project: Speakify
// Description: Generates a TTS audio file and stores it in backend storage
// =============================================================================

try {
  // [1] Collect input (GET or POST)
  $input = $_POST;
  if (empty($input)) {
    $input = $_GET;
  }

  $text     = trim($input['text'] ?? 'Bonjour et bienvenue sur Speakify.');
  $lang     = $input['lang']     ?? 'fr-FR';
  $provider = $input['provider'] ?? 'google';
  $voice    = $input['voice_id'] ?? null;
  $gender   = $input['gender']   ?? null;

  if (!$text || strlen($text) < 2) {
    throw new Exception("Invalid text input.");
  }

  // [2] Call orchestrator
  $result = TTS::synthesize([
    'text'      => $text,
    'lang'      => $lang,
    'provider'  => $provider,
    'voice_id'  => $voice,
    'gender'    => $gender,
    'user_id'   => null,
    'meta'      => []
  ]);

  // [3] Output metadata
  echo json_encode(array_merge(['success' => true], $result));

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'error' => 'TTS generation failed',
    'details' => $e->getMessage()
  ]);
}

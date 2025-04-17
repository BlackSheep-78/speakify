<?php
// =============================================================================
// File: backend/controllers/tts_generate.php
// Project: Speakify
// Description: Minimal TTS trigger for testing/demo purposes
// =============================================================================

try {
  // This method handles everything: chooses provider, runs synthesis, stores the file
  $result = TTS::generateSample();

  echo json_encode([
    'success' => true,
    'file' => $result['file'],
    'provider' => $result['provider'],
    'lang' => $result['lang']
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'error' => 'TTS generation failed',
    'details' => $e->getMessage()
  ]);
}

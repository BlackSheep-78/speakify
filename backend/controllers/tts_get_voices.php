<?php
// =============================================================================
// File: backend/controllers/tts_list_voices.php
// Project: Speakify
// Description: Lists available voices from Google TTS for inspection
// =============================================================================

try 
{
  $provider = Input::get('provider', 'google');
  $voices   = TTS::getVoices($provider);

  output([
    'success' => true,
    'provider' => $provider,
    'count' => count($voices),
    'voices' => $voices
  ]);
} 
catch (Exception $e) 
{
  http_response_code(500);
  output([
    'success' => false,
    'error' => 'Failed to get TTS voices',
    'details' => $e->getMessage()
  ]);
}


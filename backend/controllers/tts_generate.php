<?php
// =============================================================================
// 📄 File: backend/controllers/tts_generate.php
// 🎯 Purpose: Trigger a single automatic TTS generation task (for testing or batch preview)
// =============================================================================

header('Content-Type: application/json');

global $database;

$result = TTS::generateSample($database);

if (!is_array($result)) 
{
    Logger::error('ERROR_0010 TTS::generateSample returned non-array result');

    http_response_code(500);
    output([
        'success' => false,
        'error' => 'TTS generation failed: invalid result format',
        'code' => 'ERROR_0010'
    ]);
    exit;
}

if (!($result['success'] ?? false)) 
{
    Logger::warn('ERROR_0011 TTS generation returned failure: ' . ($result['error'] ?? 'no message'));

    http_response_code(400);
    output($result);
    exit;
}

// ✅ Success
http_response_code(200);
output($result);


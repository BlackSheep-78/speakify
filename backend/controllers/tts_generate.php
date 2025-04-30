<?php
// =============================================================================
// 📄 File: backend/controllers/tts_generate.php
// 🎯 Purpose: Trigger a single automatic TTS generation task (for testing or batch preview)
// =============================================================================

header('Content-Type: application/json');

try 
{
    $result = TTS::generateSample();
    echo json_encode($result);
} 
catch (Throwable $e) 
{
    Logger::error('ERROR', 'TTS generateSample failed: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'TTS generation failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
}

<?php
// =============================================================================
// 📁 File: backend/controllers/get_tts_file.php
// 🎯 Serves TTS audio files by SHA1 hash
// =============================================================================

$hash = $_GET['hash'] ?? null;

if (!$hash || !preg_match('/^[a-f0-9]{40}$/', $hash)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing hash']);
    exit;
}

$paths = TTS::buildAudioPath($hash);
$file  = $paths['full_path'];

if (!file_exists($file)) {
    http_response_code(404);
    echo json_encode(['error' => 'Audio file not found']);
    exit;
}

header('Content-Type: audio/mpeg');
header('Content-Length: ' . filesize($file));
readfile($file);
exit;

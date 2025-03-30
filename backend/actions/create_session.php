<?php
// ============================================================================
// File: speakify/backend/actions/create_session.php
// Description: Creates a new anonymous session using SessionManager.
// ============================================================================

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

file_put_contents(__DIR__ . '/../_debug_create_session.log', "Running create_session at " . date('c') . "\\n", FILE_APPEND);


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['status' => 'OK (preflight)']);
    exit;
}

// 🔧 Load config and session class
$config = require __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/SessionManager.php';
require_once __DIR__ . '/../utils/db.php'; // this sets $pdo globally

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(403);
        echo json_encode([
            'error' => 'Unauthorized',
            'details' => isset($config['debug']) && $config['debug'] ? 'Only GET allowed' : null
        ]);
        exit;
    }

    // ✅ Call static session creation method
    $token = SessionManager::createAnonymous();

    echo json_encode(['token' => $token]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Session creation failed',
        'details' => isset($config['debug']) && $config['debug'] ? $e->getMessage() : null
    ]);
}

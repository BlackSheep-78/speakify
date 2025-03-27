<?php
// ============================================================================
// File: speakify/backend/actions/create_session.php
// Description: Creates a new anonymous session using SessionManager.
// ============================================================================
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['status' => 'OK (preflight)']);
    exit;
}

$config = require __DIR__ . '/../config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(403);
        echo json_encode([
            'error' => 'Unauthorized',
            'details' => $config['debug'] ? 'Only GET allowed' : null
        ]);
        exit;
    }

    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    require_once BASEPATH . '/classes/SessionManager.php';
    $sm = new SessionManager($pdo, $config);

    $session = $sm->createAnonymous();

    echo json_encode($session);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Session creation failed',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
}

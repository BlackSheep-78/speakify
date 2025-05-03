<?php
// ============================================================================
// File: speakify/backend/actions/ping_session.php
// Description:
//     Validates whether the session token exists and is not expired.
//     Returns `{ status: 'valid' }` or `{ error: '...' }`.
//
// Usage (GET):
//     /api/index.php?action=ping_session&token=abc123
//
// Notes:
//     - Does NOT update last_activity
//     - Safe to use from public JS (no auth required)
// ============================================================================

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') 
{
    http_response_code(200);
    output(['status' => 'OK (preflight)']);
    exit;
}

$config = require __DIR__ . '/../config.php';

try 
{
    $token = Input::get('token', 'token', '');
    
    if (!$token) {
        http_response_code(400);
        output(['error' => 'Missing token']);
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

    // Validate without touching last_activity
    $session = $sm->validateToken($token, false);

    if (!$session) {
        http_response_code(404);
        output(['error' => 'Session not found or expired']);
        exit;
    }

    output(['status' => 'valid']);

} 
catch (Exception $e) 
{
    http_response_code(500);
    output([
        'error' => 'Internal server error',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
}

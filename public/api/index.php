<?php
// ============================================================================
// File: speakify/public/api/index.php
// Description:
//     Central API router for Speakify. Routes frontend requests to matching
//     backend action files and manages session validation via SessionManager.
//
// Usage:
//     GET /api/index.php?action=get_sentences&token=abc123
//
// Notes:
//     - Public actions bypass session check (e.g. create_session)
//     - Sets $auth_user_id globally (null for anonymous sessions)
//     - Action files live in /backend/actions/*.php
// ============================================================================

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// 🔧 Load config and define base constants
$config = require_once __DIR__ . '/../../backend/config.php';

if (!defined('BASEPATH')) {
    define('BASEPATH', realpath(__DIR__ . '/../../backend'));
}

// 🧼 Parse request parameters
$action = $_GET['action'] ?? null;
$token  = $_GET['token'] ?? null;

// 🔓 List of actions that bypass session check
$publicActions = ['create_session', 'validate_session'];

// 🛑 Handle missing action
if (!$action) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing action']);
    exit;
}

// 🧼 Sanitize action string
if (!preg_match('/^[a-z0-9_]+$/', $action)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

// 📂 Resolve backend action file path
$actionFile = BASEPATH . "/actions/{$action}.php";

// ❌ File not found
if (!file_exists($actionFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
    exit;
}

// 🔐 Validate token if required
try {
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

    if (!in_array($action, $publicActions)) {
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Missing session token']);
            exit;
        }

        $session = $sm->validateToken($token);
        if (!$session) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired session']);
            exit;
        }

        $auth_user_id = $session['user_id'];
    } else {
        $auth_user_id = null;
    }

    $GLOBALS['auth_user_id'] = $auth_user_id;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Session setup failed',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
}

// ✅ Run the action script
require_once $actionFile;

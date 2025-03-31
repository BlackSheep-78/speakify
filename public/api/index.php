<?php
/**
 * =============================================================================
 * 📌 Speakify Central API Router
 * =============================================================================
 * File: speakify/public/api/index.php
 *
 * Description:
 * - Handles all frontend API calls.
 * - Dispatches requests to corresponding backend action files.
 * - Manages session creation and validation via SessionManager.
 * - Loads configuration, database, and routing environment.
 * =============================================================================
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

error_log("api.php");

// 🔧 Load config and define base constants
$config = require __DIR__ . '/../../backend/config.php';

if (!defined('BASEPATH')) {
    define('BASEPATH', realpath(__DIR__ . '/../../backend'));
}

// 📦 Initialize DB connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
    exit;
}

// 🧠 Load session manager
require_once BASEPATH . '/classes/SessionManager.php';
$sm = new SessionManager($pdo, $config);

// 🧼 Parse request parameters
$action = $_GET['action'] ?? null;
$token  = $_GET['token'] ?? null;

// 🔓 Actions that bypass session validation
$publicActions = ['create_session', 'validate_session', 'register_user', 'login'];

// 🛑 Handle missing or invalid action
if (!$action) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing action']);
    exit;
}

if (!preg_match('/^[a-z0-9_]+$/', $action)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

// 📂 Map to backend action file
$actionFile = BASEPATH . "/actions/{$action}.php";

if (!file_exists($actionFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
    exit;
}

// 🔐 Token validation for protected actions
try {
    if (!in_array($action, $publicActions)) {
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Missing session token']);
            exit;
        }

        $session = $sm->validate($token);

        if (!is_array($session)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired session']);
            exit;
        }

        $auth_user_id = $session['user_id'] ?? null;
    } else {
        $auth_user_id = null;
    }

    $GLOBALS['auth_user_id'] = $auth_user_id;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Session setup failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
    exit;
}

// ✅ Execute the backend action
require_once $actionFile;

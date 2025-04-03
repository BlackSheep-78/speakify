<?php
/**
 * =============================================================================
 * 📌 Speakify Central API Router
 * =============================================================================
 * File: speakify/public/api/index.php
 * =============================================================================
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");



// 🔧 Load config and define base constants
$config = require __DIR__ . '/../../backend/config.php';


if (!defined('BASEPATH')) {
    define('BASEPATH', realpath(__DIR__ . '/../../backend'));
    error_log("📁 BASEPATH defined: " . BASEPATH);
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
    error_log("✅ DB connection OK");
} catch (Exception $e) {
    error_log("❌ DB connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
    exit;
}

// 🧠 Load session manager
require_once BASEPATH . '/classes/SessionManager.php';
error_log("🧠 SessionManager loaded");
$sm = new SessionManager($pdo, $config);

// 🧼 Parse request parameters
$action = $_GET['action'] ?? null;
$token  = $_GET['token'] ?? null;
error_log("🧭 Action: " . ($action ?? 'null') . " | Token: " . ($token ?? 'null'));

// 🔓 Actions that bypass session validation
$publicActions = ['create_session', 'validate_session', 'register_user', 'login'];

// 🛑 Handle missing or invalid action
if (!$action) {
    error_log("❌ Missing action param");
    http_response_code(400);
    echo json_encode(['error' => 'Missing action']);
    exit;
}

if (!preg_match('/^[a-z0-9_]+$/', $action)) {
    error_log("❌ Invalid action format: $action");
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

// 📂 Map to backend action file
$actionFile = BASEPATH . "/actions/{$action}.php";
error_log("📂 Action file path: $actionFile");

if (!file_exists($actionFile)) {
    error_log("❌ Action file NOT FOUND");
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
    exit;
}

// 🔐 Token validation for protected actions
try {
    if (!in_array($action, $publicActions)) {
        error_log("🔐 Protected action, validating token");

        if (!$token) {
            error_log("❌ Missing token for protected action");
            http_response_code(401);
            echo json_encode(['error' => 'Missing session token']);
            exit;
        }

        $session = $sm->validate($token);

        if (!is_array($session)) {
            error_log("❌ Invalid session or expired token");
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired session']);
            exit;
        }

        $auth_user_id = $session['user_id'] ?? null;
        error_log("✅ Authenticated user ID: $auth_user_id");
    } else {
        error_log("🆓 Public action, no auth needed");
        $auth_user_id = null;
    }

    $GLOBALS['auth_user_id'] = $auth_user_id;

} catch (Exception $e) {
    error_log("🔥 Session validation error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Session setup failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
    exit;
}

// ✅ Execute the backend action
error_log("🚀 Including action file: $actionFile");

error_log(file_get_contents($actionFile));

require_once $actionFile;

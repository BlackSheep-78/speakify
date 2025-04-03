<?php
/**
 * =============================================================================
 * 📌 Speakify Central API Router
 * =============================================================================
 * File: speakify/public/api/index.php
 * =============================================================================
 */

require_once __DIR__ . '/../../backend/init.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// 📦 Initialize DB and session manager
$pdo = Database::getInstance()->getConnection();
$sm  = new SessionManager($pdo);

$action = $_GET['action'] ?? null;
$token  = $_GET['token'] ?? null;

$publicActions = ['create_session', 'validate_session', 'register_user', 'login'];

if (!$action || !preg_match('/^[a-z0-9_]+$/', $action)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing action']);
    exit;
}

$actionFile = BASEPATH . "/backend/controllers/{$action}.php";

if (!file_exists($actionFile)) {
    error_log("❌ Action file NOT FOUND");
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
    exit;
}

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

        $GLOBALS['auth_user_id'] = $session['user_id'] ?? null;
    } else {
        $GLOBALS['auth_user_id'] = null;
    }
} catch (Exception $e) {
    error_log("🔥 Session validation error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Session setup failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
    exit;
}

// 🚀 Run controller
require_once $actionFile;

<?php
/**
 * =============================================================================
 * 🧠 Speakify – Central API Router
 * =============================================================================
 * 📁 File: /speakify/public/api/index.php
 * 📦 Purpose: Acts as the public entry point for all API requests in production.
 *
 * ✅ Features:
 * - Handles routing to backend controllers via `action` parameter
 * - Validates session tokens for protected routes
 * - Supports CORS for cross-origin frontend access
 * - Centralized DB + session manager initialization
 * - Handles both public and protected actions
 *
 * 🚫 Important:
 * - This file is the ONLY public API access point. `backend/api.php` is not used.
 * - Only controllers in `/backend/controllers/` should be routed here.
 *
 * 🔐 Auth:
 * - Protected routes require a valid session token.
 * - On success, `$GLOBALS['auth_user_id']` is set and available to controller logic.
 *
 * 🔍 Dependencies:
 * - /init.php loads autoloaders and class definitions
 * - Database, SessionManager, Logger must be initialized before routing
 * =============================================================================
 */

require_once __DIR__ . '/../../init.php';

// 🌐 CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// 🚫 Handle CORS Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(["status" => "CORS preflight OK"]);
    exit;
}

// 📦 Initialize dependencies
$pdo = Database::getInstance()->getConnection();
$sm  = new SessionManager($pdo);

// 🧾 Get parameters
$action = $_GET['action'] ?? null;
$token  = $_GET['token'] ?? null;

// 🎯 Valid public endpoints that don't require authentication
$publicActions = [
    'create_session',
    'validate_session',
    'register_user',
    'login',
    'logout',
    'playlists' // optional if `get_playlists.php` does not enforce auth
];

// 🚫 Validate action
if (!$action || !preg_match('/^[a-z0-9_]+$/', $action)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing action']);
    exit;
}

// 📄 Locate controller file
$actionFile = BASEPATH . "/backend/controllers/{$action}.php";
if (!file_exists($actionFile)) {
    Logger::log("❌ Action file NOT FOUND: {$actionFile}", __FILE__, __LINE__);
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
    exit;
}

// 🔒 Auth validation (only for protected routes)
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
    Logger::log("🔥 Session validation error: " . $e->getMessage(), __FILE__, __LINE__);
    http_response_code(500);
    echo json_encode([
        'error' => 'Session setup failed',
        'details' => DEBUG ? $e->getMessage() : null
    ]);
    exit;
}

// ✅ Include controller and execute
require_once $actionFile;

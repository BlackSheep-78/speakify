<?php

/**
 * =============================================================================
 * 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
 * =============================================================================
 * File: speakify/backend/api.php (diagnostic version)
 * Project: Speakify
 *
 * Description:
 * Handles API routing for diagnostic and development purposes. This file includes:
 * - CORS preflight handling
 * - Anonymous session creation
 * - Routing to backend controllers
 * - Fallback loading of mock playlist JSON data
 * - Router logging for incoming API actions
 * =============================================================================
 */

require_once __DIR__ . '/init.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// 🔍 Log routing decision
file_put_contents(__DIR__ . '/router.log', date('Y-m-d H:i:s') . ' - ACTION: ' . ($_GET['action'] ?? 'none') . ' METHOD: ' . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);

// 🛑 CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(["status" => "CORS preflight success"]);
    exit;
}

// ✅ Define base controller path
define('CONTROLLER_PATH', BASEPATH . '/controllers');

// ✅ Handle API routing
$action = $_GET['action'] ?? null;

if ($action === 'create_session') {
    file_put_contents(__DIR__ . '/router.log', "➡ Routing to create_session.php\n", FILE_APPEND);
    require_once BASEPATH . '/controllers/create_session.php';
    exit;
}

if ($action) {
    $controllerFile = CONTROLLER_PATH . "/{$action}.php";
    if (file_exists($controllerFile)) {
        file_put_contents(__DIR__ . '/router.log', "✅ Controller found: {$controllerFile}\n", FILE_APPEND);
        require_once $controllerFile;
        exit;
    } else {
        file_put_contents(__DIR__ . '/router.log', "❌ Controller NOT found: {$controllerFile}\n", FILE_APPEND);
        echo json_encode(["error" => "Invalid action or controller file missing"]);
        exit;
    }
}

// 🔁 Fallback: return static mock JSON (playlists)
$data_file = __DIR__ . '/../public/data/playlists.json';

if (!file_exists($data_file)) {
    echo json_encode(["error" => "Data file not found"]);
    exit;
}

$json_data = file_get_contents($data_file);
$data = json_decode($json_data, true);

if (!$data) {
    echo json_encode(["error" => "Invalid JSON format"]);
    exit;
}

echo json_encode($data, JSON_PRETTY_PRINT);
exit;

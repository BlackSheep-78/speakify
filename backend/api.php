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
 * - Fallback loading of mock playlist JSON data
 * - Router logging for incoming API actions
 *
 * ✅ Used during:
 * - Local development
 * - Frontend simulation before full backend is integrated
 * - JSON API structure testing
 * =============================================================================
 */

// ============================================================================
// File: speakify/backend/api.php (diagnostic version)
// ============================================================================
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

// ✅ Handle session creation
if (isset($_GET['action']) && $_GET['action'] === 'create_session') {
    file_put_contents(__DIR__ . '/router.log', "➡ Routing to create_session.php\n", FILE_APPEND);
    require_once __DIR__ . '/actions/create_session.php';
    exit;
}

// 🔁 Fallback: return static JSON
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
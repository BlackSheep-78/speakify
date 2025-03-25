<?php
// API router: /public/api/index.php

header('Content-Type: application/json');

$config = require __DIR__ . '/../../config.php';

$action = $_GET['action'] ?? null;
$token = $_GET['token'] ?? null;

if (!$action) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing action']);
    exit;
}

if ($token !== $config['admin_token']) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (!preg_match('/^[a-z0-9_]+$/', $action)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

$actionFile = BASEPATH . "/actions/{$action}.php";

if (!file_exists($actionFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown action']);
    exit;
}

require_once $actionFile;

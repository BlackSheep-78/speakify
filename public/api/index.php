<?php

// ============================================
// Project: Speakify
// File: /public/api/index.php
// Description: Central API router for all incoming public requests.
// ============================================

// [0] Loading settings and variables
require_once __DIR__ . '/../../init.php';

// [1] CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// [2] Handle CORS Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') 
{
    http_response_code(200);
    echo json_encode(["status" => "CORS preflight OK"]);
    exit;
}

$action = Input::get('action', 'string');
$token  = Input::get('token', 'token');

// [3] Action validation
if (!Actions::isValid($action)) 
{
    http_response_code(400);
    echo json_encode(['success' => false, 
                      'error' => 'Unknown action ['.$action.']', 
                      'code' => 'ERROR_0003',
                      'tip'=>'Add this action to Actions.php']);
    exit;
}



// [4] Session creation / validation
$database = Database::init();
$sessionManager = new SessionManager(['db' => $database]);
$session = $sessionManager->check($token);

// [5] If the action is protected, but session is invalid or not logged in:
if (Actions::isProtected($action) && (!$session['success'] || empty($session['token']))) 
{
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized access',
        'code' => 'ERROR_0004',
        'tip' => 'This action requires a protected session (anonymous or authenticated). Your session is missing, expired, or invalid.'
      ]);
    exit;
}

// [6] Locate controller file
$controllerPath = BASEPATH . "/backend/controllers/{$action}.php";

if (!file_exists($controllerPath)) 
{
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => "Action controller not found",
        'code' => 'ERROR_0005'
    ]);
    exit;
}

// [7] Include controller
require_once $controllerPath;

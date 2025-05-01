<?php
// =============================================================================
// File: backend/controllers/login.php
// Project: Speakify
// Description: Handles user login and session upgrade
// =============================================================================

global $database;

$email    = Input::post('email', '');  // Correct method for POST input
$password = Input::post('password', '');  // Correct method for POST input
$token    = Input::get('token', '');  

// 🔐 Basic validation
if (!$email || !$password) 
{
    echo json_encode(['success' => false, 'error' => 'Missing credentials']);
    exit;
}



$sessionManager = new SessionManager(['db' => $database]);
$session = $sessionManager->check($token);

// 🔁 If session is expired or invalid, silently generate a new one
if (!$session) 
{
    $session = $sessionManager->create();
    $token = $session['token'];
}

$loginService = new LoginService(['db' => $database]);
$result = $loginService->authenticate($email, $password, $token);

if (!$result['success']) 
{
    echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Authentication failed']);
    exit;
}

// 🔁 Upgrade current session with user_id
$sessionManager->upgrade($token, $result['user_id']);

// ✅ Return session + user data
echo json_encode([
    'success' => true,
    'token' => $token,
    'user_id' => $result['user_id'],
    'email' => $result['email'],
    'name' => $result['name'],
    'last_login' => $result['last_login'] ?? null,
    'logged_in' => true
]);
exit;

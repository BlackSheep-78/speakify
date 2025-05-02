<?php
// =============================================================================
// Project: Speakify
// File: /backend/controllers/validate_session.php
// Description: Validates a session token and returns login status + user info
// =============================================================================


header('Content-Type: application/json');

global $database;

$token    = Input::get('token', 'token', '');
$service  = new LoginService(['db' => $database]);
$response = $service->validate($token);

if (isset($response['error'])) 
{
    $sessionManager = new SessionManager(['db' => $database]);
    $new = $sessionManager->create(); // ✅ FIXED: instance call
    $token = $new['token'];

    $response = [
        'success'   => true,
        'logged_in' => false,
        'token'     => $token
    ];
}

http_response_code(isset($response['error']) ? 401 : 200);
echo json_encode($response);

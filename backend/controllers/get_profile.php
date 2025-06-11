<?php
// ============================================================================
// File: backend/actions/get_profile.php
// Description: Returns user profile info if token is valid
// ============================================================================

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

if(!$response['logged_in'])
{
  $response['redirect'] = "login-profile";
}

http_response_code(isset($response['error']) ? 401 : 200);
output($response);

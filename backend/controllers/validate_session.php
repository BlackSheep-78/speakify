<?php
// =============================================================================
// 🔐 File: validate_session.php
// 📁 Location: backend/actions/validate_session.php
// 🎯 Purpose: Validate a session token and return login status
// 📦 Input: GET `token`
// 📤 Output: JSON with success, token, loggedin, (optional) user info
// =============================================================================

header('Content-Type: application/json');

$token = $_GET['token'] ?? '';
$service = new LoginService(Database::init());

$response = $service->validate($token);

if (isset($response['error'])) {
    $new = SessionManager::create();
    $token = $new['token'];
    $response = [
        'success'   => true,
        'logged_in' => false,
        'token'     => $token
    ];
}

http_response_code(isset($response['error']) ? 401 : 200);
echo json_encode($response);

<?php
// =============================================================================
// Project     : Speakify
// File        : /backend/controllers/update_user.php
// Description : Updates the current user's name, email, and optionally password
// =============================================================================

header('Content-Type: application/json');

global $database;

$name     = Input::post('name', 'string');
$email    = Input::post('email', 'email');
$password = Input::post('password', 'string');
$token    = Input::get('token', 'token', '');

$service = new LoginService(['db' => $database]);
$response = $service->validate($token);

if (!$response['logged_in']) 
{
    http_response_code(403);
    output(['success' => false, 'error' => 'Not logged in']);
}

$userId = $response['user_id'];

$userModel = new UserModel(['db'=>$database]);

$updateData = [
    'name'  => $name,
    'email' => $email
];

if (!empty($password)) {
    $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
}

$success = $userModel->updateUser($userId, $updateData);

if ($success) {
    output(['success' => true]);
} else {
    http_response_code(500);
    output(['success' => false, 'error' => 'Failed to update user']);
}

<?php
// ============================================================================
// File: backend/actions/get_profile.php
// Description: Returns user profile info if token is valid
// ============================================================================

header('Content-Type: application/json');

$token = Input::get('token', 'token', '');

if (!$token) 
{
  http_response_code(400);
  output(['error' => 'Missing token']);
  exit;
}

$sessionModel = new SessionModel();
$user = $sessionModel->getUserProfile($token);

if (!$user) 
{
  http_response_code(401);
  output(['error' => 'Invalid token']);
  exit;
}

output([
  'name' => $user['name'],
  'email' => $user['email'],
  'last_login' => $user['last_login']
]);

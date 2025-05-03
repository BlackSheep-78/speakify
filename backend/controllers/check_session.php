<?php
// =============================================================================
// File: actions/check_session.php
// Description: Checks if a session token is valid and not expired.
// =============================================================================

header('Content-Type: application/json');

$token = Input::get('token', 'token', '');

if (!$token) 
{
  http_response_code(400);
  output(['error' => 'Missing token']);
  exit;
}

$sessionModel = new SessionModel();
$session = $sessionModel->validateToken($token);

if (!$session) {
  http_response_code(401);
  output(['error' => 'Invalid or expired session']);
  exit;
}

output([
  'status' => 'valid',
  'user_id' => $session['user_id']
]);

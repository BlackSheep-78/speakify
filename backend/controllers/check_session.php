<?php
// =============================================================================
// File: actions/check_session.php
// Description: Checks if a session token is valid and not expired.
// =============================================================================

header('Content-Type: application/json');

$token = $_GET['token'] ?? '';

if (!$token) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

$sessionModel = new SessionModel();
$session = $sessionModel->validateToken($token);

if (!$session) {
  http_response_code(401);
  echo json_encode(['error' => 'Invalid or expired session']);
  exit;
}

echo json_encode([
  'status' => 'valid',
  'user_id' => $session['user_id']
]);

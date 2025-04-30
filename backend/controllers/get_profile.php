<?php
// ============================================================================
// File: backend/actions/get_profile.php
// Description: Returns user profile info if token is valid
// ============================================================================

header('Content-Type: application/json');

$token = $_GET['token'] ?? '';

if (!$token) 
{
  http_response_code(400);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

$sessionModel = new SessionModel();
$user = $sessionModel->getUserProfile($token);

if (!$user) 
{
  http_response_code(401);
  echo json_encode(['error' => 'Invalid token']);
  exit;
}

echo json_encode([
  'name' => $user['name'],
  'email' => $user['email'],
  'last_login' => $user['last_login']
]);

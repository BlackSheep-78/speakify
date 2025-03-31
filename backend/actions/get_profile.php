<?php
// ============================================================================
// File: backend/actions/get_profile.php
// Description: Returns user profile info if token is valid
// ============================================================================

require_once BASEPATH . '/init.php';

$token = $_GET['token'] ?? '';

if (!$token) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT users.name, users.email, MAX(sessions.last_activity) AS last_login
    FROM sessions
    JOIN users ON users.id = sessions.user_id
    WHERE sessions.token = :token
    GROUP BY users.id
  ");
  $stmt->execute(['token' => $token]);
  $user = $stmt->fetch();

  if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token']);
    exit;
  }

  echo json_encode([
    'name' => $user['name'],
    'email' => $user['email'],
    'last_login' => $user['last_login']
  ]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}

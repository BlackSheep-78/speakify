<?php
// =============================================================================
// File: actions/check_session.php
// Description: Checks if a session token is valid and not expired.
// =============================================================================

$token = $_GET['token'] ?? '';

if (!$token) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT * FROM `sessions` WHERE `token` = :token AND `expires_at` > NOW()");
  $stmt->execute(['token' => $token]);
  $session = $stmt->fetch();

  if (!$session) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired session']);
    exit;
  }

  echo json_encode(['status' => 'valid', 'user_id' => $session['user_id']]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}

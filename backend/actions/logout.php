<?php
// =============================================================================
// File: actions/logout.php
// Description: Destroys a user session by token.
// =============================================================================

$token = $_GET['token'] ?? '';

if (!$token) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

try {
  $stmt = $pdo->prepare("DELETE FROM `sessions` WHERE `token` = :token");
  $stmt->execute(['token' => $token]);

  echo json_encode(['status' => 'logged out']);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}

<?php
// =============================================================================
// File: actions/login.php
// Description: Logs in a user by verifying credentials and creating a session.
// =============================================================================

require_once BASEPATH . '/utils/hash.php'; // For password_verify if needed

error_log("login.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing email or password']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
  }

  $token = bin2hex(random_bytes(32));
  $expires = date('Y-m-d H:i:s', time() + 3600 * 24); // 24h

  $stmt = $pdo->prepare("INSERT INTO `sessions` (`user_id`, `token`, `expires_at`) VALUES (:user_id, :token, :expires)");
  $stmt->execute([
    'user_id' => $user['id'],
    'token' => $token,
    'expires' => $expires
  ]);

  echo json_encode([
    'token' => $token,
    'user_id' => $user['id'],
    'name' => $user['name']
  ]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}

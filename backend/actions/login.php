<?php
// =============================================================================
// File: actions/login.php
// Description: Logs in a user by verifying credentials and upgrading session.
// =============================================================================

require_once BASEPATH . '/utils/hash.php'; // For password_verify if needed
require_once BASEPATH . '/classes/SessionManager.php';

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$token = $_GET['token'] ?? null;

error_log("login.php");

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

  // 🔁 Upgrade anonymous session if token exists
  if ($token) {
    SessionManager::upgrade($token, $user['id']);
  } else {
    // Or create a brand new session token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600 * 24); // 24h

    $stmt = $pdo->prepare("INSERT INTO `sessions` (`user_id`, `token`, `expires_at`) VALUES (:user_id, :token, :expires)");
    $stmt->execute([
      'user_id' => $user['id'],
      'token' => $token,
      'expires' => $expires
    ]);
  }

  echo json_encode([
    'success' => true,
    'token' => $token,
    'user_id' => $user['id'],
    'name' => $user['name']
  ]);

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    'error' => 'Server error',
    'details' => DEBUG ? $e->getMessage() : null
  ]);
}

<?php
// =============================================================================
// File: actions/login.php
// Description: Logs in a user by verifying credentials and upgrading session.
// =============================================================================

require_once BASEPATH . '/utils/hash.php';
require_once BASEPATH . '/classes/SessionManager.php';

header('Content-Type: application/json');

error_log("🔐 login.php called");

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$token = $_GET['token'] ?? null;

error_log("📨 Input: email = $email");
error_log("📦 Token received: " . ($token ?? 'null'));

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing email or password']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch();

  if (!$user) {
    error_log("❌ No user found for email: $email");
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
  }

  if (!password_verify($password, $user['password_hash'])) {
    error_log("❌ Password mismatch for email: $email");
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
  }

  error_log("✅ User authenticated: ID = " . $user['id']);

  if ($token) {
    error_log("🔁 Attempting session upgrade for token: $token");

    $upgrade = $pdo->prepare("UPDATE `sessions` SET `user_id` = :uid WHERE `token` = :token");
    $upgrade->execute(['uid' => $user['id'], 'token' => $token]);

    $verify = $pdo->prepare("SELECT * FROM `sessions` WHERE token = :token AND user_id = :uid LIMIT 1");
    $verify->execute(['token' => $token, 'uid' => $user['id']]);

    if (!$verify->fetch()) {
      error_log("❌ Session upgrade failed for token: $token");
      throw new Exception('Session upgrade failed');
    }

    error_log("✅ Session successfully upgraded");
  } else {
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 86400);

    error_log("🆕 Creating new session with token: $token");

    $create = $pdo->prepare("INSERT INTO `sessions` (`user_id`, `token`, `expires_at`) VALUES (:uid, :token, :expires)");
    $create->execute(['uid' => $user['id'], 'token' => $token, 'expires' => $expires]);

    error_log("✅ New session created successfully");
  }

  echo json_encode([
    'success' => true,
    'token' => $token,
    'user_id' => $user['id'],
    'name' => $user['name']
  ]);

  error_log("🎉 Login successful for user: " . $user['id']);

} catch (Exception $e) {
  error_log("🔥 Login failed: " . $e->getMessage());
  http_response_code(500);
  echo json_encode([
    'error' => 'Login failed',
    'details' => DEBUG ? $e->getMessage() : null
  ]);
}

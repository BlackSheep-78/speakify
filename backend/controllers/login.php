<?php
// =============================================================================
// 🔐 File: login.php
// 📁 Location: backend/controllers/login.php
// 🎯 Purpose: API endpoint for user login and session upgrade
// 📦 Input: JSON body with `email`, `password`; optional `token` (GET)
// 📤 Output: JSON with login status, session token, user info, and loggedin flag
// =============================================================================

header('Content-Type: application/json');

// 📥 Parse input
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$token = $_GET['token'] ?? null;

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing email or password']);
  exit;
}

// 🔐 Authenticate user
$service = new LoginService($database);
$response = $service->authenticate($email, $password, $token);

// ❌ Failed authentication
if (isset($response['error'])) {
  http_response_code(401);
  echo json_encode($response);
  exit;
}

// ✅ ENFORCE RULE 10 — Upgrade session if token is present
if ($token) {
  $session = SessionManager::validate($token);
  Logger::info("Session validation result: " . json_encode($session));

  if ($session && !$session['logged_in']) 
  {
    //Logger::info("🔄 Upgrading session for token: " . $token);
    SessionManager::upgrade($token, $response['user_id']);
    //Logger::info("✅ Session upgraded for user: " . $response['user_id']);
  } else {
    //Logger::info("ℹ️ Session already logged in or invalid for token: " . $token);
  }
}

// ✅ Success
echo json_encode($response);

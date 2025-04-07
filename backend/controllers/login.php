<?php
// =============================================================================
// 🔐 File: login.php
// 📁 Location: backend/actions/login.php
// 🎯 Purpose: API endpoint for user login and session upgrade
// 📦 Input: JSON body with `email`, `password`; optional `token` (GET)
// 📤 Output: JSON with login status, session token, user info, and loggedin flag
// =============================================================================

require_once BASEPATH . '/backend/classes/LoginService.php';
require_once BASEPATH . '/backend/classes/SessionManager.php'; // ✅ REQUIRED

Logger::info("login.php");

header('Content-Type: application/json');
error_log("🔐 login.php called");

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$token = $_GET['token'] ?? null;

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing email or password']);
  exit;
}

// Log the token to check if it's passed correctly
Logger::info("Token received: " . $token);

// ✅ ENFORCE RULE 10 — upgrade session if success and token is present
if (($token)) {
    // Validate session before upgrading
    $session = SessionManager::validate($token);  // Validate token and get session info
    
    // Log the session validation result
    Logger::info("Session validation result: " . json_encode($session));

    if ($session) {
        if (!$session['logged_in']) {  // Ensure it's an anonymous session
            // Log that we are upgrading the session
            Logger::info("Upgrading session for token: " . $token);
            
            // Upgrade the anonymous session with the user_id
            SessionManager::upgrade($token, $response['user_id']);
            Logger::info("🔄 Session upgraded for user " . $response['user_id']);
        } 
        else {
            Logger::info("🔄 Session is already logged in for token: " . $token);
        }
    } else {
        // Log if session is not found or expired
        Logger::info("🔄 Invalid or expired session for token: " . $token);
    }
}

$service = new LoginService($pdo);
$response = $service->authenticate($email, $password, $token);

// Handle authentication failure
if (isset($response['error'])) {
  http_response_code(401);
  echo json_encode($response);
  exit;
}

// Return the successful response
echo json_encode($response);

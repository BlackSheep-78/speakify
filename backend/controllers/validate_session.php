<?php
// =============================================================================
// 🔐 File: validate_session.php
// 📁 Location: backend/actions/validate_session.php
// 🎯 Purpose: Validate a session token and return login status
// 📦 Input: GET `token`
// 📤 Output: JSON with success, token, loggedin, (optional) user info
// =============================================================================

header('Content-Type: application/json');

$token = $_GET['token'] ?? null;
$service = new LoginService($pdo);

$response = $service->validate($token);

http_response_code(isset($response['error']) ? 401 : 200);
echo json_encode($response);

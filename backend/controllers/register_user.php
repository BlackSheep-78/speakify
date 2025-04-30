<?php
/**
 * =============================================================================
 * 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
 * =============================================================================
 * File: backend/actions/register_user.php
 * Project: Speakify
 *
 * Description:
 * Handles new user registration from the public frontend.
 * - Accepts JSON POST payload with email, password, and name
 * - Validates and checks for duplicate emails
 * - Hashes password securely (bcrypt)
 * - Inserts user into the `users` table
 * - Returns JSON response with status
 * =============================================================================
 */

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$name = $input['name'] ?? 'Anonymous';

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => 'Missing email or password']);
  exit;
}

$userModel = new UserModel();

if ($userModel->emailExists($email)) 
{
  http_response_code(409);
  echo json_encode(['success' => false, 'error' => 'Email already exists']);
  exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

if (!$userModel->createUser($email, $hash, $name)) 
{
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'User creation failed']);
  exit;
}

echo json_encode([
  'success' => true,
  'status' => 'registered',
  'email' => $email,
  'name' => $name
]);

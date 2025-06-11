<?php
// =============================================================================
// File: register_user.php
// Project: Speakify
// Description: Handles new user registration from the public frontend.
// =============================================================================

header('Content-Type: application/json');

$email    = Input::post('email', 'email');  // Sanitized input for email
$password = Input::post('password', 'string');  // Sanitized input for password
$name     = Input::post('name', 'string');  // Sanitized input for name

if (!$email || !$password) 
{
  http_response_code(400);
  output(['success' => false, 'error' => 'Missing email or password']);
  exit;
}

$userModel = new UserModel(['db' => $database]);

if ($userModel->emailExists($email)) 
{
  http_response_code(409);
  output(['success' => false, 'error' => 'Email already exists']);
  exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

if (!$userModel->createUser($email, $hash, $name)) 
{
  http_response_code(500);
  output(['success' => false, 'error' => 'User creation failed']);
  exit;
}

output([
  'success' => true,
  'status' => 'registered',
  'email' => $email,
  'name' => $name
]);

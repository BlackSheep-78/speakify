<?php
// =============================================================================
// File: actions/register.php
// Description: Registers a new user (for testing/dev only)
// =============================================================================

require_once BASEPATH . '/utils/hash.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$name = $_POST['name'] ?? 'Anonymous';

// Validate that email and password are provided
if (!$email || !$password) {
  http_response_code(400);  // Bad Request
  echo json_encode(['error' => 'Missing email or password']);
  exit;
}

try {
  // Check if email already exists
  $stmt = $pdo->prepare("SELECT id FROM `users` WHERE `email` = :email");
  $stmt->execute(['email' => $email]);
  if ($stmt->fetch()) {
    http_response_code(409);  // Conflict (email already exists)
    echo json_encode(['error' => 'Email already exists']);
    exit;
  }

  // Hash the password
  $hash = password_hash($password, PASSWORD_BCRYPT);

  // Insert the new user into the database
  $stmt = $pdo->prepare("INSERT INTO `users` (`email`, `password_hash`, `name`) VALUES (:email, :hash, :name)");
  $stmt->execute([
    'email' => $email,
    'hash' => $hash,
    'name' => $name
  ]);

  // Return success response
  echo json_encode(['status' => 'registered', 'email' => $email, 'name' => $name]);
} catch (PDOException $e) {
  http_response_code(500);  // Internal Server Error
  echo json_encode(['error' => 'Registration failed', 'details' => $e->getMessage()]);
}
?>

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

require_once dirname(__DIR__) . '/init.php';

// Log incoming raw request (for debugging only)
file_put_contents(__DIR__ . '/../register.debug.log', file_get_contents('php://input'));

// Accept JSON body
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$name = $input['name'] ?? 'Anonymous';

// Validate input
if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => 'Missing email or password']);
  exit;
}

try {
  // Check if email exists
  $stmt = $pdo->prepare("SELECT id FROM `users` WHERE `email` = :email");
  $stmt->execute(['email' => $email]);
  if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'error' => 'Email already exists']);
    exit;
  }

  // Hash password
  $hash = password_hash($password, PASSWORD_BCRYPT);

  // Insert new user
  $stmt = $pdo->prepare("INSERT INTO `users` (`email`, `password_hash`, `name`) VALUES (:email, :hash, :name)");
  $stmt->execute([
    'email' => $email,
    'hash' => $hash,
    'name' => $name
  ]);

  echo json_encode(['success' => true, 'status' => 'registered', 'email' => $email, 'name' => $name]);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'Registration failed', 'details' => $e->getMessage()]);
}
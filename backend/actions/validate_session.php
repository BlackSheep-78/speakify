<?php
/*
  ========================================================================
  File: speakify/backend/actions/register.php
  Description: Handles user registration. Accepts email, password, and name.
  Includes password hashing and email existence check.
  ========================================================================
*/

// Include your database connection (replace with your actual DB connection setup)
require_once 'db_connection.php';  // Make sure this file connects to your database

// Handle POST request (user registration)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data (email, password, and name)
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);

    // Basic validation (check if fields are empty)
    if (empty($email) || empty($password) || empty($name)) {
        echo json_encode(['error' => 'All fields are required.']);
        exit();
    }

    // Hash the password using bcrypt for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['error' => 'Email already exists.']);
        exit();
    }

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, name, created_at, is_active) 
                           VALUES (:email, :password_hash, :name, NOW(), 1)");

    $stmt->execute([
        'email' => $email,
        'password_hash' => $hashed_password,
        'name' => $name
    ]);

    // Return success message
    echo json_encode(['success' => 'User registered successfully.']);
    exit();
}

?>

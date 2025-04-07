<?php
// =============================================================================
// 📦 Class: LoginService
// 📁 Location: backend/classes/LoginService.php
// 🎯 Purpose: Handles user authentication and session management logic
// =============================================================================

class LoginService {
  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;

    Logger::info("LoginService");
  }

  public function authenticate($email, $password, $existingToken = null): array {
    // 🔍 Step 1: Find user
    $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return ['error' => 'Invalid credentials'];
    }

    // 🎯 Step 2: Handle session upgrade (Rule 10)
    $token = $existingToken;

    if ($token) {
        SessionManager::upgrade($token, $user['id']);
    } else {
        // Create a new session if no token was provided
        $newSession = SessionManager::create();
        $token = $newSession['token'];
        SessionManager::upgrade($token, $user['id']);
    }

    // ✅ Step 3: Return auth response
    return [
        'success'   => true,
        'logged_in' => true,
        'token'     => $token,
        'user_id'   => $user['id'],
        'name'      => $user['name'],
        'email'     => $user['email'] ?? null
    ];
}


  public function validate($token) 
  {
    // ============================================================================
    // 🔎 Method: validate(string $token)
    // 🎯 Purpose:
    //    Validates a session token, determines if the session is active, and
    //    returns authentication context (logged in or anonymous).
    //
    // 📥 Input:
    //    - $token (string): Session token to validate.
    //
    // 📤 Output:
    //    - If valid:
    //        {
    //          "success": true,
    //          "logged_in": true|false,
    //          "token": "...",
    //          "user_id": (optional),
    //          "name": (optional),
    //          "email": (optional)
    //        }
    //    - If invalid:
    //        {
    //          "error": "..."
    //        }
    //
    // ✅ Rules:
    //   1. Token must be provided. If missing, return an error.
    //   2. Token must match a record in `sessions`. If not found, return an error.
    //   3. If session includes `user_id > 0`, set "logged_in": true.
    //   4. If session is anonymous, set "logged_in": false and return only the token.
    //   5. If logged in, also return user's name, email, and user_id.
    //   6. No session upgrade or renewal is performed by this method.
    // ============================================================================

    if (!$token) {
      return [ 'error' => 'Missing session token' ];
    }
  
    $stmt = $this->pdo->prepare("SELECT * FROM `sessions` WHERE token = :token LIMIT 1");
    $stmt->execute(['token' => $token]);
    $session = $stmt->fetch();
  
    if (!$session) {
      return [ 'error' => 'Invalid or expired session' ];
    }
  
    $isLoggedIn = isset($session['user_id']) && $session['user_id'] > 0;
  
    $result = [
      'success' => true,
      'logged_in' => $isLoggedIn,
      'token' => $session['token']
    ];
  
    if ($isLoggedIn) {
      $userStmt = $this->pdo->prepare("SELECT name, email FROM `users` WHERE id = :uid LIMIT 1");
      $userStmt->execute(['uid' => $session['user_id']]);
      $user = $userStmt->fetch();
  
      if ($user) {
        $result['user_id'] = $session['user_id'];
        $result['name'] = $user['name'];
        $result['email'] = $user['email'] ?? null;
      }
    }
  
    return $result;
  }
}

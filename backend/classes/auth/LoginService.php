<?php
// =============================================================================
// 📦 Class: LoginService
// 📁 Location: backend/classes/auth/LoginService.php
// 🎯 Purpose: Handles user authentication and session management logic
// =============================================================================
// 🔎 Method: validate(string \$token)
// 🎯 Purpose:
//    Validates a session token, determines if the session is active, and
//    returns authentication context (logged in or anonymous).
//
// 📥 Input:
//    - \$token (string): Session token to validate.
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
// =============================================================================

class LoginService {
  private $db;

  public function __construct($database) {
    $this->db = $database;
  }

  public function authenticate($email, $password, $existingToken = null): array 
  {
      // 🔍 Step 1: Find user
      $user = $this->db->file('/users/select_by_email.sql')
                       ->replace(':EMAIL', $email, 's')
                       ->result(['fetch' => 'assoc'])[0] ?? null;
  
      if (!$user || !password_verify($password, $user['password_hash'])) {
          return ['error' => 'Invalid credentials'];
      }
  

    
      // 🎯 Step 2: Reuse existing anonymous session if possible
      $session = SessionManager::validate($existingToken);
   
  
      if ($session && !$session['logged_in']) {
          // Upgrade anonymous session
          SessionManager::upgrade($existingToken, $user['id']);
          $token = $existingToken;
      } else {
          // No session or already linked — create new
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
    if (!$token) {
      return [ 'error' => 'Missing session token' ];
    }

    $session = $this->db->file('/session/select_valid_by_token.sql')
                        ->replace(':TOKEN', $token, 's')
                        ->result(['fetch' => 'assoc'])[0] ?? null;

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
      $user = $this->db->file('/users/select_profile_by_id.sql')
                       ->replace(':USER_ID', $session['user_id'], 'i')
                       ->result(['fetch' => 'assoc'])[0] ?? null;

      if ($user) {
        $result['user_id'] = $session['user_id'];
        $result['name'] = $user['name'];
        $result['email'] = $user['email'] ?? null;
      }
    }

    return $result;
  }

  public static function deleteOldestLogs($db = null): void
  {
    $db = $db ?: Database::init();

    // Step 1: Count total logs
    $total = $db->file('/logger/count_all_logs.sql')
                ->result(['fetch' => 'assoc'])[0]['total'] ?? 0;

    if ($total <= 0) return;

    // Step 2: Calculate 10% (rounded up, minimum 1)
    $limit = max(1, (int) ceil($total * 0.10));

    // Step 3: Get the oldest log IDs
    $ids = $db->file('/logger/select_oldest_log_ids.sql')
              ->replace(':LIMIT', $limit, 'i')
              ->result(['fetch' => 'column']);

    if (count($ids) > 0) {
        $db->file('/logger/delete_logs_by_ids.sql')
           ->rawBind($ids)
           ->result();

        Logger::info("🗑️ Deleted {$limit} oldest logs (IDs: " . implode(',', $ids) . ").");
    }
  }

  public static function upgrade($token, $user_id) 
  {
    $db = Database::init();

    $session = $db->file('/session/select_user_id_by_token.sql')
                  ->replace(':TOKEN', $token, 's')
                  ->result(['fetch' => 'assoc'])[0] ?? null;

    if ($session && !$session['user_id']) {
        $db->file('/session/update_user_id_by_token.sql')
           ->replace(':USER_ID', $user_id, 'i')
           ->replace(':TOKEN', $token, 's')
           ->result();

        Logger::info("Session upgraded for token: {$token}");
    } else {
        Logger::info("Session already upgraded or not found for token: {$token}");
    }
  }
}

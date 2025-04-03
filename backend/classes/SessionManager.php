<?php

/**
 * ==============================================================================
 * 📌 SessionManager Class – Session Logic Rules (Speakify)
 * ==============================================================================
 *
 * Handles all session-related logic including validation, creation, updating,
 * and purging for both anonymous and logged-in users.
 *
 * ✅ Core Rules:
 * 1. All sessions begin as anonymous.
 * 2. Session tokens are reused as long as valid.
 * 3. Expired/inactive sessions result in a new token being generated.
 * 4. Logged-in users retain the same session (upgraded with user_id).
 * 5. All API calls must pass the token as `GET` or `POST`.
 *
 * 💡 Used by: validate_session.php, create_session.php, login.php, etc.
 * ==============================================================================
 */

class SessionManager {
  private $pdo;
  private $config;

  public function __construct($pdo = null, $config = []) {
    $this->pdo = $pdo ?? $GLOBALS['pdo'];
    $this->config = $config;
  }

  /**
   * 🔄 Create a new anonymous session
   */
  public static function create(): string {
    global $pdo;

    $token = bin2hex(random_bytes(32));
    $now = date('Y-m-d H:i:s');
    $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $stmt = $pdo->prepare("
      INSERT INTO sessions (token, created_at, last_activity, expires_at)
      VALUES (:token, :created, :activity, :expires)
    ");
    $stmt->execute([
      'token' => $token,
      'created' => $now,
      'activity' => $now,
      'expires' => $expires
    ]);

    return $token;
  }

  /**
   * 👤 Alias for clarity – Create anonymous session
   */
  public static function createAnonymous(): string {
    return self::create();
  }

  /**
   * ✅ Validate token and update session activity
   */
  public static function validate(string $token): ?array
  {
      global $pdo;
  
      if (!$token) {
          error_log("SessionManager::validate called with no token");
          return null;
      }
  
      if (!($pdo instanceof PDO)) {
          error_log("❌ \$pdo is not an instance of PDO. Got: " . gettype($pdo));
          return null;
      }
  
      try {
          $stmt = $pdo->prepare("SELECT * FROM sessions WHERE token = :token LIMIT 1");
          $stmt->execute(['token' => $token]);
          $session = $stmt->fetch();
  
          if (!$session) {
              return null;
          }
  
          $pdo->prepare("UPDATE sessions SET last_activity = NOW() WHERE id = :id")
              ->execute(['id' => $session['id']]);
  
          return $session;
  
      } catch (Exception $e) {
          error_log("SessionManager::validate error: " . $e->getMessage());
          return null;
      }
  }
  
  

  /**
   * 🔁 Validate or create a new session if invalid
   */
  public static function validateOrCreate(string &$token): array {
    $session = self::validate($token);

    if (!$session) {
      $token = self::create();
      $session = self::validate($token);
    }

    return $session;
  }

  /**
   * 🔄 Manual "touch" to keep session alive
   */
  public static function touch(string $token): void {
    global $pdo;

    if (!$token) return;

    $stmt = $pdo->prepare("UPDATE sessions SET last_activity = NOW() WHERE token = :token");
    $stmt->execute(['token' => $token]);
  }

  /**
   * ⬆️ Upgrade anonymous session to logged-in by setting user_id
   */
  public static function upgrade(string $token, int $user_id): bool {
    global $pdo;

    $stmt = $pdo->prepare("SELECT user_id FROM sessions WHERE token = :token");
    $stmt->execute(['token' => $token]);
    $row = $stmt->fetch();

    if (!$row) return false;
    if ($row['user_id']) return true; // Already upgraded

    $update = $pdo->prepare("UPDATE sessions SET user_id = :user_id WHERE token = :token");
    return $update->execute([
      'user_id' => $user_id,
      'token' => $token
    ]);
  }

  /**
   * 🧪 Instance method version of session creation
   */
  public function createInstanceSession(): string {
    $token = bin2hex(random_bytes(32));
    $now = date('Y-m-d H:i:s');
    $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

    $stmt = $this->pdo->prepare("
      INSERT INTO sessions (token, created_at, last_activity, expires_at)
      VALUES (:token, :created, :activity, :expires)
    ");
    $stmt->execute([
      'token' => $token,
      'created' => $now,
      'activity' => $now,
      'expires' => $expires
    ]);

    return $token;
  }

  /**
   * 🧹 Delete expired sessions
   */
  public static function purgeExpired(): bool {
    global $pdo;

    $stmt = $pdo->prepare("DELETE FROM sessions WHERE expires_at < NOW()");
    return $stmt->execute();
  }
}

?>

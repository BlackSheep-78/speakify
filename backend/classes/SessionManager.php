<?php
/*
  ============================================================================
  📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
  ============================================================================
  This block defines the core behavior of the Speakify session lifecycle.
  It MUST always be enforced consistently across all session logic (create,
  validate, touch, and expiration). Removing or altering these rules may
  result in broken session handling, security issues, or invalid client states.
  ============================================================================
  
  ============================================================================
  SessionManager - Speakify Session Lifecycle Rules
  ============================================================================

  🧠 Purpose:
    Manages anonymous session lifecycle: creation, validation, expiration,
    and touch (activity update).

  ✅ Session Lifecycle Guidelines:

  1. Session is identified by a 64-character random token (stored in `sessions.token`).
  2. A session is valid if:
     - It exists in the database.
     - The `expires_at` datetime is in the future (if set).
  3. On each request:
     - `validate(token)` is called to check the session.
     - If valid, it can be reused.
     - `touch(token)` may be called to update `last_activity`.
  4. If no valid session is found:
     - `create()` or `createAnonymous()` is called to insert a new row.
     - `created_at`, `last_activity`, and `expires_at` are set.
  5. Session expiration defaults to 24 hours after creation unless otherwise configured.
  6. All validation failures should fall back to session regeneration logic.
  7. Only static methods are used in the application unless instance use is explicitly needed.
  8. Session tokens are stored client-side in `localStorage` as `speakify_token`.

  ============================================================================
  File: speakify/backend/classes/SessionManager.php
  Description: Handles session creation, validation, and touch updates.
  Supports both static and instance-based usage.
  ============================================================================
*/

class SessionManager {
  private $pdo;
  private $config;

  public function __construct($pdo = null, $config = []) {
    $this->pdo = $pdo ?? $GLOBALS['pdo'];
    $this->config = $config;
  }

  // Static methods
  public static function create() {
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

  public static function createAnonymous() {
    return self::create();
  }

  public static function validate($token) {
    global $pdo;

    if (!$token) return false;

    $stmt = $pdo->prepare("SELECT * FROM sessions WHERE token = :token LIMIT 1");
    $stmt->execute(['token' => $token]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$session) return false;

    // Check expiration
    $now = time();
    $expiresAt = strtotime($session['expires_at']);
    if ($expiresAt !== false && $now > $expiresAt) {
      return false;
    }

    // Touch session on success
    $update = $pdo->prepare("UPDATE sessions SET last_activity = NOW() WHERE id = ?");
    $update->execute([$session['id']]);

    return $session; // return full session array
  }

  public static function touch($token) {
    global $pdo;

    if (!$token) return;

    $stmt = $pdo->prepare("UPDATE sessions SET last_activity = NOW() WHERE token = :token");
    $stmt->execute(['token' => $token]);
  }

  // Instance methods (optional)
  public function createInstanceSession() {
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
}
?>

<?php
// ============================================================================
// ⚠️ DO NOT REMOVE OR MODIFY THIS HEADER
// This file defines session lifecycle rules for Speakify. These rules are
// essential for consistency between frontend and backend session management.
// Changes to this header must be approved and documented in project.md.
// ============================================================================
// 📁 File: backend/classes/SessionManager.php
// 📦 Project: Speakify
// ============================================================================
// ✅ SessionManager Rules (MUST STAY CONSISTENT)
// ----------------------------------------------------------------------------
// 1. All session tokens are 64-char secure hex strings.
// 2. Anonymous sessions must be allowed and created automatically.
// 3. A session is "logged_in" only if it is associated with a user_id.
// 4. `validate()` must return full session details + login state.
// 5. `create()` must return a new token and timestamps.
// 6. `upgrade()` must associate a session with a user_id.
// 7. `destroy()` must delete the session from the DB entirely.
// 8. Only `SessionManager::validate()` should determine login state.
// 9. Logging out removes `user_id` from the session but keeps the session active.
// 10. Logging in upgrades the existing anonymous session (same token) by setting `user_id`.
// ============================================================================
// ✅ Public Methods Overview
// ----------------------------------------------------------------------------
// • create()                  → Creates a new anonymous session
// • validate($token)          → Checks if session is valid and returns session state
// • validateOrCreate(&$token) → Validates session or creates a new one if invalid
// • upgrade($token, $user_id) → Links a session to a user (marks as logged in)
// • destroy($token)           → Deletes a session completely from the DB
// • logout($token)            → Removes user_id from the session, keeps token
// ============================================================================



class SessionManager {

    // create
    public static function create() {
        $token = bin2hex(random_bytes(32));
        $now = date('Y-m-d H:i:s');
        $expires = date('Y-m-d H:i:s', strtotime('+8 hours'));

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO sessions (token, last_activity, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$token, $now, $expires]);

        return [
            'success' => true,
            'token' => $token,
            'last_activity' => $now,
            'expires_at' => $expires,
            'logged_in' => false
        ];
    }

    // validate
    public static function validate($token) {
        if (!$token) return null;

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM sessions WHERE token = :token AND expires_at > NOW() LIMIT 1");
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        // 🔁 Occasionally clean up old sessions (1/1000 chance)
        if (random_int(1, 1000) === 1) {
            self::cleanupOldSessions($db); // Call with same DB handle
        }

        // 🧹 Occasionally delete oldest session (1/1000)
        if (random_int(1, 1000) === 1) {
            self::deleteOldestLogs($db);
        }


        // Update last activity
        $stmt = $db->prepare("UPDATE sessions SET last_activity = NOW() WHERE token = :token");
        $stmt->execute([':token' => $token]);

        $user_id = $row['user_id'] ?? null;
        $name = null;

        if ($user_id) {
            $stmt = $db->prepare("SELECT name FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $name = $user['name'];
            }
        }

        return [
            'valid' => true,
            'token' => $row['token'],
            'user_id' => $user_id,
            'name' => $name,
            'last_activity' => $row['last_activity'],
            'expires_at' => $row['expires_at'],
            'logged_in' => !empty($user_id)
        ];
    }

    public static function cleanupOldSessions($db = null) {
        if (!$db) {
            $db = Database::getInstance()->getConnection();
        }
    
        $stmt = $db->prepare("DELETE FROM sessions WHERE expires_at < NOW()");
        $stmt->execute();
    
        Logger::log("🧹 Old sessions cleaned up.");
    }

    public static function deleteOldestLogs($db = null) {
        if (!$db) {
            $db = Database::getInstance()->getConnection();
        }
    
        $stmt = $db->query("SELECT id FROM logs ORDER BY id ASC LIMIT 1");
        $oldest = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($oldest) {
            $stmt = $db->prepare("DELETE FROM logs WHERE id = :id");
            $stmt->execute([':id' => $oldest['id']]);
            Logger::log("🗑️ Oldest log ID {$oldest['id']} deleted.");
        }
    }
    
    

    // validateOrCreate
    public static function validateOrCreate(&$token) 
    {
      $session = self::validate($token);
  
      if (!$session) {
          $new = self::create();           // Create new anonymous session
          $token = $new['token'];          // Update the token in caller's scope
          return self::validate($token);   // Return full session state
      }
  
      return $session;
    }
  
    // upgrade
    public static function upgrade($token, $user_id) {
        $db = Database::getInstance()->getConnection();

        // Ensure that the session is anonymous (user_id is NULL)
        $stmt = $db->prepare("SELECT user_id FROM sessions WHERE token = :token");
        $stmt->execute([':token' => $token]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);

        // Only upgrade if the session is anonymous (user_id is NULL)
        if ($session && !$session['user_id']) {
            // Upgrade the session by setting the user_id
            $stmt = $db->prepare("UPDATE sessions SET user_id = :user_id WHERE token = :token");
            $stmt->execute([
                ':user_id' => $user_id,
                ':token' => $token
            ]);
            Logger::info("Session upgraded for token: " . $token);
        } else {
            Logger::info("Session already upgraded or not found for token: " . $token);
        }
    }



    // destroy
    public static function destroy($token) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM sessions WHERE token = :token");
        $stmt->execute([':token' => $token]);
    }

    // logout
    public static function logout($token) 
    {
        Logger::log("#### logout");

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE sessions SET user_id = NULL WHERE token = :token");
        $stmt->execute([':token' => $token]);
        return ['success' => true];
    }
}

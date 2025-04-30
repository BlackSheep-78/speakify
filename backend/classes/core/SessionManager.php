<?php
// ========================================== 
// Project: Speakify
// File: backend/classes/SessionManager.php
// Description: Handles session lifecycle: create, validate, upgrade, logout.
// ==========================================

 
class SessionManager 
{
    private static ?array $activeSession = null;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
    
        if (!$this->db instanceof Database) {
            throw new Exception("ClassName requires a valid 'db' instance.");
        }
    
        // Optional: store other values
        $this->lang_id = isset($options['lang_id']) ? (int)$options['lang_id'] : null;
        $this->user_id = $options['user_id'] ?? null;
    }

    // Validates a 64-char token or creates a new anonymous session.
    public function check(?string $token): array
    {
        if (!is_string($token) || strlen($token) !== 64) 
        {
            $session = $this->create();
        } 
        else 
        {
            $session = $this->validate($token);
            if (!$session || !$session['success']) 
            {
                $session = $this->create();
            }
        }
    
        // Final fallback — never return null
        return is_array($session) ? $session : $this->create();
    }
    
    public function create(): array 
    {
        $sessionModel = new SessionModel(['db' => $this->db]);
        return $sessionModel->create();
    }
    
    // Validates the session token and updates its expiration.
    public function validate(string $token): array 
    {
        if (empty($token)) 
        {
            return ['success' => false, 'error' => 'Missing session token'];
        }

        // [1]: Validate token in SessionModel
        $sessionModel = new SessionModel(['db' => $this->db]);
        $session = $sessionModel->validateToken($token);

        // [2]: If invalid, return error
        if (!$session || !isset($session['token'])) {
            return ['success' => false, 'error' => 'Invalid session'];
        }

        // [3]: If valid, touch the session to extend expiration and update activity
        $sessionModel->touch($token);

        // [4]: Return session data
        return [
            'success' => true,
            'token' => $session['token'],
            'user_id' => $session['user_id'],
            'expires_at' => $session['expires_at'],
            'data' => $session['data'] ?? null
        ];
    }

    
    public function validateOrCreate(&$token) 
    {
        if (self::$activeSession !== null) return self::$activeSession;

        $session = self->validate($token);
        if (!$session) {
            $new = self::create();
            $token = $new['token'];
            $session = self::validate($token);
        }

        self::$activeSession = $session;
        return $session;
    }

    public function upgrade($token, $user_id) 
    {
        $sessionModel = new SessionModel(['db' => $this->db]);
        $sessionModel->upgradeUserSession($token, $user_id);
    }

    public function destroy($token) 
    {
        $sessionModel = new SessionModel(['db' => $this->db]);
        $sessionModel->destroy($token);
    }

    public function logout($token) 
    {
        $sessionModel = new SessionModel(['db' => $this->db]);
        return $sessionModel->logout($token);
    }

    public function getCurrentUser(?string $token): ?array 
    {
        if (!$token) return null;

        $session = self::validate($token);
        Logger::debug(json_encode($session, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if (!$session || empty($session['user_id'])) return null;

        try {
            $userModel = new UserModel();
            return $userModel->getProfileById($session['user_id']);
        } catch (Exception $e) {
            Logger::error("Failed to load user from session: " . $e->getMessage());
            return null;
        }
    }

    public function getUserIdFromToken(?string $token): ?int 
    {
        if (!$token) return null;

        $session = self::validate($token);
        if (!is_array($session) || isset($session['error']) || !isset($session['user_id'])) return null;

        return (int) $session['user_id'];
    }
}

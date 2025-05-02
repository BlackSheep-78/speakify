<?php
// Project: Speakify
// File: /backend/classes/models/SessionModel.php
// Description: Data access layer for session token validation

class SessionModel {
    private $db;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
    
        if (!$this->db instanceof Database) 
        {
            throw new Exception(static::class . " requires a valid 'db' instance.");
        }
    }

    public function create(): array 
    {
        $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $token   = bin2hex(random_bytes(32));
        $now     = date('Y-m-d H:i:s');
        $expires = date('Y-m-d H:i:s', time() + 3600 * 8);

        $this->db->file('/session/insert_session.sql')
        ->replace(':TOKEN', $token, 's')
        ->replace(':NOW', $now, 's')
        ->replace(':EXPIRES', $expires, 's')
        ->replace(':IP', $ip, 's')
        ->result(); 

        return [
            'success'       => true,
            'token'         => $token,
            'last_activity' => $now,
            'expires_at'    => $expires,
            'logged_in'     => false
        ];
    }

    public function validateToken(string $token): ?array 
    {
        $result =  $this->db->file('/session/validate_token.sql')
                        ->replace(':TOKEN', $token, 's')
                        ->result(['fetch' => 'row']);

        return is_array($result) ? $result : null; // 🔥 fix here
    }

    public function touchSession(string $token): bool 
    {
        return $this->db->file('/session/update_last_activity.sql')
                        ->replace(':TOKEN', $token, 's')
                        ->result(['fetch' => 'none']);
    }

    public function getUserProfile(string $token): ?array 
    {
        return $this->db->file('/session/get_user_profile_by_token.sql')
                        ->replace(':TOKEN', $token, 's')
                        ->result(['fetch' => 'row']);
    }

    public function upgradeUserSession(string $token, int $userId): bool
    {
        // Use the pre-existing SQL file to update the session's user ID
        $this->db->file('/session/update_user_id_by_token.sql')
            ->replace(':USER_ID', $userId, 'i')
            ->replace(':TOKEN', $token, 's')
            ->result();
    
        return true;
    }

    public function logout(string $token): bool
    {
        $result = $this->db->file('/session/logout.sql')
                           ->replace(':TOKEN', $token, 's')
                           ->result(['fetch' => 'none']);
    
        return $result === true; // ✅ strict boolean return
    }
    
    public function touch(string $token): void
    {
        $this->db->file('/session/touch_session.sql')
                 ->replace(':TOKEN', $token, 's')
                 ->result(['fetch' => 'none']);
    }
    
}

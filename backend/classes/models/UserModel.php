<?php
// Project: Speakify
// File: /backend/classes/models/UserModel.php
// Description: User database operations

class UserModel {
    private $db;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
    
        if (!$this->db instanceof Database) 
        {
            throw new Exception(static::class . " requires a valid 'db' instance.");
        }
    }

    public function createUser(string $email, string $hash, string $name): bool 
    {
        return $this->db->file('/users/insert_user.sql')
                        ->replace(':EMAIL', $email, 's')
                        ->replace(':HASH', $hash, 's')
                        ->replace(':NAME', $name, 's')
                        ->result(['fetch' => 'none']);
    }

    public function getProfileById(int $userId): ?array
    {
        $result = $this->db->file('/users/select_profile_by_id.sql')
                           ->replace(':USER_ID', $userId, 'i')
                           ->result(['fetch' => 'row']);
    
        return $result ?: null;
    }

    public function findByEmail(string $email)
    {
        // Fetch a single row from the database (no need for [0])
        $result = $this->db->file('/users/select_by_email.sql')
                            ->replace(':EMAIL', $email, 's')
                            ->result(['fetch' => 'row']); // Ensures only one row returned
    
        //Logger::debug($result);
        
        return $result ?: null; // Return user or null if no result
    }
    
}

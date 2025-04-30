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

    public function emailExists(string $email): bool 
    {
        $result = $this->db->file('/users/select_by_email.sql')
                           ->replace(':EMAIL', $email, 's')
                           ->result(['fetch' => 'row']);
        return !empty($result);
    }

    public function createUser(string $email, string $hash, string $name): bool 
    {
        return $this->db->file('/users/insert_user.sql')
                        ->replace(':EMAIL', $email, 's')
                        ->replace(':HASH', $hash, 's')
                        ->replace(':NAME', $name, 's')
                        ->result(['fetch' => 'none']);
    }
}

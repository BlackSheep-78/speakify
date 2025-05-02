<?php
// Project: Speakify
// File: /backend/classes/models/LoggerModel.php
// Description: Handles error log writing to the logs table

class LoggerModel 
{
    private $db;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
    
        if (!$this->db instanceof Database) {
            throw new Exception(static::class . " requires a valid 'db' instance.");
        }
    }

    public function write(string $message, string $level = 'info', string $file = 'unknown', int $line = 0): void
    {
        $this->db->file('/logger/insert_log.sql')
            ->replace(':LEVEL', $level, 's')
            ->replace(':MESSAGE', $message, 's')
            ->replace(':FILE', $file, 's')
            ->replace(':LINE', (string)$line, 'i')
            ->replace(':CREATED', date('Y-m-d H:i:s'), 's')
            ->result();
    }
    
}

<?php
// ========================================== 
// Project: Speakify
// File: backend/classes/core/Logger.php
// Description: Static logger for quick debug and info-level logging.
// ==========================================

class Logger
{
    public static function log(mixed $message, string $level = 'info'): void
    {
        if (!is_string($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    
        // 🧠 Get file and line from caller
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? null;
        $file = $trace['file'] ?? 'unknown';
        $line = $trace['line'] ?? 0;
        
        try 
        {
            $model = self::getLoggerModel();
            $model->write($message, $level, $file, (int)$line);
        } 
        catch (Throwable $e) 
        {
            error_log('[LOGGER ERROR #1] ' . $e->getMessage());
        }
    }

    public static function debug(mixed $message): void
    {
        self::log($message, 'debug');
    }
    
    public static function info(mixed $message): void
    {
        self::log($message, 'info');
    }

    public static function warn(mixed $message): void
    {
        self::log($message, 'warn');
    }
    
    public static function error(mixed $message): void
    {
        self::log($message, 'error');
    }

    private static function getLoggerModel(): LoggerModel
    {
        global $database;

        return new LoggerModel(['db' => $database]);
    }
}

<?php
/**
 * =============================================================================
 * 📁 File: /speakify/backend/classes/Logger.php
 * 📦 Project: Speakify
 * 📌 Description: Unified logging utility that writes to database and auto-fills context.
 * =============================================================================
 */

class Logger
{

    // ✏️ Core logging function — logs to DB

    public static function log(string $level, string $msg, string $file = '', int $line = 0): void
    {
        // Auto-detect file/line if missing
        if ($file === '' || $line === 0) 
        {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? [];
            $file = $trace['file'] ?? 'unknown';
            $line = $trace['line'] ?? 0;
        }

        $timestamp = date('Y-m-d H:i:s');

        try 
        {
            Database::init()
                ->file('/logger/insert_log.sql')
                ->replace(':LEVEL', $level, 's')
                ->replace(':MESSAGE', $msg, 's')
                ->replace(':FILE', $file, 's')
                ->replace(':LINE', $line, 'i')
                ->replace(':CREATED', $timestamp, 's')
                ->result();
        } 
        catch (Exception $e) 
        {
            error_log("Logger::log() failed: " . $e->getMessage());
        }
    }


    // Info-level message
    public static function info(string $msg, string $file = '', int $line = 0): void
    {
        self::log('INFO', $msg, $file, $line);
    }

    /**
     * ⚠️ Warning-level message
     */
    public static function warning(string $msg, string $file = '', int $line = 0): void
    {
        self::log('WARNING', $msg, $file, $line);
    }

    /**
     * ❌ Error-level message
     */
    public static function error(string $msg, string $file = '', int $line = 0): void
    {
        self::log('ERROR', $msg, $file, $line);
    }

    /**
     * 🐞 Debug-level message
     */
    public static function debug(string $msg, string $file = '', int $line = 0): void
    {
        if (defined('DEBUG') && DEBUG) {
            self::log('DEBUG', $msg, $file, $line);
        }
    }
}

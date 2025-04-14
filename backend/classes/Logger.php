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
    private static ?PDO $pdo = null;

    /**
     * 🔧 Initialize with a PDO instance for DB logging
     */
    public static function init(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    /**
     * ✏️ Core logging function — logs to DB
     */
    public static function log(string $level, string $msg, string $file = '', int $line = 0): void
    {
        // Auto-detect file/line if missing
        if ($file === '' || $line === 0) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? [];
            $file = $trace['file'] ?? 'unknown';
            $line = $trace['line'] ?? 0;
        }

        $timestamp = date('Y-m-d H:i:s');

        if (!self::$pdo) {
            error_log("Logger::log(): PDO not initialized. Message: $msg");
            return;
        }

        try {
            $stmt = self::$pdo->prepare("
                INSERT INTO logs (level, message, file, line, created_at)
                VALUES (:level, :message, :file, :line, :created_at)
            ");
            $stmt->execute([
                ':level' => $level,
                ':message' => $msg,
                ':file' => $file,
                ':line' => $line,
                ':created_at' => $timestamp
            ]);
        } catch (Exception $e) {
            error_log("Logger::log() failed: " . $e->getMessage());
        }
    }

    /**
     * ℹ️ Info-level message
     */
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

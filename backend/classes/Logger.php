<?php

// =============================================================================
// 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
// =============================================================================
// File: backend/classes/Logger.php
// Project: Speakify
//
// Description:
// Centralized logger for all errors, exceptions, and debug messages.
//
// 🧾 Responsibilities:
//  1. Provide static logging methods (log, info, warning, error, debug, exception)
//  2. Automatically log PHP errors and exceptions via set_error_handler()
//  3. Support logging to both the database and a fallback log file
//  4. Store logs in the `logs` table using injected PDO instance
//  5. Output debug logs to `/logs/app.log` when enabled
//  6. Use BASEPATH to resolve all file paths safely
//  7. Allow graceful fallback to file logging if DB logging fails
// =============================================================================

class Logger
{
  private static ?PDO $pdo = null;
  private static bool $logToFile = true;
  private static string $logFile = BASEPATH . '/logs/app.log';

  // 1️⃣ Initialize with PDO and register handlers
  public static function init(?PDO $pdo = null): void {
    self::$pdo = $pdo;
    set_error_handler([self::class, 'handleError']);
    set_exception_handler([self::class, 'handleException']);
  }

  // 2️⃣ Handle PHP errors
  public static function handleError($errno, $errstr, $errfile, $errline): bool {
    $level = match ($errno) {
      E_ERROR, E_USER_ERROR     => 'ERROR',
      E_WARNING, E_USER_WARNING => 'WARNING',
      E_NOTICE, E_USER_NOTICE   => 'NOTICE',
      default                   => 'LOG',
    };
    self::log($level, $errstr, $errfile, $errline);
    return true; // prevent PHP default error handling
  }

  // 3️⃣ Handle uncaught exceptions
  public static function handleException(Throwable $e): void {
    self::log('EXCEPTION', $e->getMessage(), $e->getFile(), $e->getLine());
  }

  // 4️⃣ Core logger
  public static function log(string $level, string $message, string $file = '', int $line = 0, array $context = []): void {
    $timestamp = date('Y-m-d H:i:s');
    $summary = "[$timestamp][$level] $message @ $file:$line";

    // Log to DB if available
    if (self::$pdo) {
      try {
        $stmt = self::$pdo->prepare("
          INSERT INTO logs (level, message, file, line, created_at)
          VALUES (:level, :message, :file, :line, NOW())
        ");
        $stmt->execute([
          'level'   => $level,
          'message' => $message,
          'file'    => $file,
          'line'    => $line
        ]);
      } catch (PDOException $err) {
        // If DB logging fails, fallback to file
        self::logToFile("[Logger DB fail] " . $err->getMessage());
      }
    }

    // Also write to file (if enabled)
    if (self::$logToFile) {
      self::logToFile($summary);
    }
  }

  // 5️⃣ Write log entry to file
  private static function logToFile(string $entry): void {
    try {
      $logDir = dirname(self::$logFile);
      if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true); // Create the logs directory if missing
      }
      file_put_contents(self::$logFile, $entry . PHP_EOL, FILE_APPEND);
    } catch (\Throwable) {
      // Fail silently
    }
  }

  // 6️⃣ Shorthand methods
  public static function info(string $msg, string $file = '', int $line = 0): void {
    self::log('INFO', $msg, $file, $line);
  }

  public static function warning(string $msg, string $file = '', int $line = 0): void {
    self::log('WARNING', $msg, $file, $line);
  }

  public static function error(string $msg, string $file = '', int $line = 0): void {
    self::log('ERROR', $msg, $file, $line);
  }

  public static function debug(string $msg, string $file = '', int $line = 0): void {
    self::log('DEBUG', $msg, $file, $line);
  }

  public static function exception(Throwable $e): void {
    self::handleException($e);
  }
}

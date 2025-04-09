<?php
// 📁 speakify/backend/utils/logger.php

require_once __DIR__ . '/../classes/Database.php';

function log_error_to_db($level, $message, $file = null, $line = null, $context = null)
{
    try {
        $db = Database::getInstance()->getConnection();

        if (!$db || !($db instanceof mysqli)) {
            Logger::log("❌ DB not initialized for error logging.");
            return;
        }

        $stmt = $db->prepare("INSERT INTO error_logs (level, message, file, line, context) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $level,
            $message,
            $file,
            $line,
            $context ? json_encode($context) : null
        ]);
    } catch (Throwable $e) {
        Logger::log("🛑 Failed to log error to DB: " . $e->getMessage());
    }
}


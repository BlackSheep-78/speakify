<?php

// =============================================================================
// 📌 TEST FILE — LOGGER
// =============================================================================
// File: backend/test/test_logger.php
// Project: Speakify
//
// Description:
// Tests the Logger class with sample log levels.
// =============================================================================

require_once __DIR__ . '/../../init.php'; // loads BASEPATH, config, pdo, Logger, etc.


Logger::init($GLOBALS['pdo']);

// Force test logs
Logger::info("🧪 Logger info test");
Logger::debug("🧪 Logger debug test");
Logger::warning("⚠️ Logger warning test");
Logger::error("🔥 Logger error test");

try {
  throw new Exception("🚨 Test exception logging");
} catch (Exception $e) {
  Logger::exception($e);
}

echo "✅ Logger test completed. Check DB logs table and /logs/app.log\n";

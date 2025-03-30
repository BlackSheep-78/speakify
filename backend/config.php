<?php
/**
 * =============================================================================
 * 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
 * =============================================================================
 * File: backend/config.php
 * Project: Speakify
 *
 * Description:
 * Loads and validates configuration from `config.json` in the project root.
 *
 * ✅ Automatically:
 * - Creates the file if missing (with defaults)
 * - Warns on insecure placeholder values
 * - Exposes all config values as constants (safe defines)
 * - Supports API keys via `api_keys.admin` and `api_keys.frontend`
 * - Defines BASE_PATH as the backend root for consistent file access
 * =============================================================================
 */

// Define base path to the backend folder
defined('BASE_PATH') or define('BASE_PATH', realpath(__DIR__));

// Locate config file at: speakify/config.json
$configPath = dirname(__DIR__) . '/config.json';

// Default config to generate if none exists
$defaultConfig = [
    "env" => "development",
    "debug" => true,

    "db" => [
        "host" => "127.0.0.1",
        "name" => "database-name",
        "user" => "root",
        "pass" => ""
    ],

    "api_keys" => [
        "admin" => "change_this_token",
        "frontend" => "change_this_frontend_key"
    ],

    "session" => [
        "expiry_days" => 7
    ],

    "api" => [
        "rate_limit" => false,
        "default_lang_id" => 39
    ],

    "frontend" => [
        "allow_origins" => ["*"],
        "allow_methods" => ["GET", "POST", "OPTIONS"],
        "allow_headers" => ["Content-Type", "Authorization"]
    ]
];

// Auto-create if file is missing
if (!file_exists($configPath)) {
    file_put_contents($configPath, json_encode($defaultConfig, JSON_PRETTY_PRINT));
    die("⚠️ Config file 'config.json' was created. Please fill in your values.");
}

// Debug protection: readable? valid JSON?
if (!is_readable($configPath)) {
    die("❌ config.json exists but is not readable: $configPath");
}

$json = file_get_contents($configPath);
if ($json === false) {
    die("❌ Failed to read config.json from: $configPath");
}

$config = json_decode($json, true);
if (!is_array($config)) {
    die("❌ Failed to parse 'config.json'. Please check the syntax.");
}

// Merge legacy admin_token (if exists) into api_keys.admin
if (!isset($config['api_keys'])) {
    $config['api_keys'] = [
        "admin" => $config['admin_token'] ?? "change_this_token",
        "frontend" => "change_this_frontend_key"
    ];
}

// Placeholder warnings
$warnings = [];
if ($config['db']['user'] === 'root') $warnings[] = "⚠️ DB user is still 'root'";
if ($config['db']['pass'] === '') $warnings[] = "⚠️ DB password is empty";
if (str_starts_with($config['api_keys']['admin'], 'change_this')) $warnings[] = "⚠️ Admin API key is a placeholder";
if (str_starts_with($config['api_keys']['frontend'], 'change_this')) $warnings[] = "⚠️ Frontend API key is a placeholder";
if (!empty($warnings)) echo implode("\n", $warnings) . "\n";

// Define constants safely
defined('ENV') or define('ENV', $config['env']);
defined('DEBUG') or define('DEBUG', $config['debug']);

defined('DB_HOST') or define('DB_HOST', $config['db']['host']);
defined('DB_NAME') or define('DB_NAME', $config['db']['name']);
defined('DB_USER') or define('DB_USER', $config['db']['user']);
defined('DB_PASS') or define('DB_PASS', $config['db']['pass']);

defined('API_KEYS') or define('API_KEYS', $config['api_keys']);

defined('SESSION_EXPIRY_DAYS') or define('SESSION_EXPIRY_DAYS', $config['session']['expiry_days']);

defined('API_RATE_LIMIT') or define('API_RATE_LIMIT', $config['api']['rate_limit']);
defined('API_DEFAULT_LANG_ID') or define('API_DEFAULT_LANG_ID', $config['api']['default_lang_id']);

defined('FRONTEND_ORIGINS') or define('FRONTEND_ORIGINS', $config['frontend']['allow_origins']);
defined('FRONTEND_METHODS') or define('FRONTEND_METHODS', $config['frontend']['allow_methods']);
defined('FRONTEND_HEADERS') or define('FRONTEND_HEADERS', $config['frontend']['allow_headers']);

// ✅ Return full config as array for use in code
return $config;

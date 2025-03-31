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
        "name" => "speakify",         // 🔄 Use real default project DB name
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

// 🔄 Create the file if it's missing
if (!file_exists($configPath)) {
    file_put_contents($configPath, json_encode($defaultConfig, JSON_PRETTY_PRINT));
    die("⚠️ Configuration file 'config.json' was created. Please update it with your project values.");
}

// 🧪 Basic checks
if (!is_readable($configPath)) {
    die("❌ Configuration file exists but is not readable: $configPath");
}

$json = file_get_contents($configPath);
if ($json === false) {
    die("❌ Failed to read config.json from: $configPath");
}

$config = json_decode($json, true);
if (!is_array($config)) {
    die("❌ Failed to parse 'config.json'. Please check for JSON syntax errors.");
}

// 🔧 Backwards compatibility: merge legacy admin_token
if (!isset($config['api_keys'])) {
    $config['api_keys'] = [
        "admin" => $config['admin_token'] ?? "change_this_token",
        "frontend" => "change_this_frontend_key"
    ];
}

// ⚠️ Developer placeholder warnings
$warnings = [];
if ($config['db']['user'] === 'root') $warnings[] = "⚠️ DB user is still 'root'";
if ($config['db']['pass'] === '') $warnings[] = "⚠️ DB password is empty";
if (str_starts_with($config['api_keys']['admin'], 'change_this')) $warnings[] = "⚠️ Admin API key is still a placeholder";
if (str_starts_with($config['api_keys']['frontend'], 'change_this')) $warnings[] = "⚠️ Frontend API key is still a placeholder";

if (!empty($warnings)) {
    echo implode("\n", $warnings) . "\n";
}

// ✅ Safe constant definitions
defined('ENV')                  || define('ENV', $config['env']);
defined('DEBUG')                || define('DEBUG', $config['debug']);

defined('DB_HOST')              || define('DB_HOST', $config['db']['host']);
defined('DB_NAME')              || define('DB_NAME', $config['db']['name']);
defined('DB_USER')              || define('DB_USER', $config['db']['user']);
defined('DB_PASS')              || define('DB_PASS', $config['db']['pass']);

defined('API_KEYS')             || define('API_KEYS', $config['api_keys']);
defined('SESSION_EXPIRY_DAYS')  || define('SESSION_EXPIRY_DAYS', $config['session']['expiry_days']);

defined('API_RATE_LIMIT')       || define('API_RATE_LIMIT', $config['api']['rate_limit']);
defined('API_DEFAULT_LANG_ID')  || define('API_DEFAULT_LANG_ID', $config['api']['default_lang_id']);

defined('FRONTEND_ORIGINS')     || define('FRONTEND_ORIGINS', $config['frontend']['allow_origins']);
defined('FRONTEND_METHODS')     || define('FRONTEND_METHODS', $config['frontend']['allow_methods']);
defined('FRONTEND_HEADERS')     || define('FRONTEND_HEADERS', $config['frontend']['allow_headers']);

// ✅ Return config array for use in init.php
return $config;

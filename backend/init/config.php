<?php

// =============================================================================
// 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
// =============================================================================
// File: backend/config.php
// Project: Speakify
//
// Description:
// Loads and validates configuration from `config.json` in the project root.
//
// 🧾 Responsibilities:
//  1. Expect BASEPATH to be defined in the caller (typically init.php)
//  2. Locate `config.json` at BASEPATH/config.json
//  3. Create the config file if missing, with sensible defaults
//  4. Validate and parse the config contents as JSON
//  5. Check for insecure placeholder values and emit warnings
//  6. Provide safe backwards compatibility for legacy keys
//  7. Define global constants for easy access throughout the backend
//  8. Assign $GLOBALS['CREDENTIALS']
//  9. Return the full config array for use by init.php or others
// =============================================================================

// 2️⃣ Locate config file at: BASEPATH/config.json
$configPath = BASEPATH . '/config.json';

// 3️⃣ Default config to generate if none exists
$defaultConfig = [
  "env" => "development",
  "debug" => true,
  "db" => [
    "host" => "127.0.0.1",
    "name" => "speakify",
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

// 4️⃣ Create the file if it's missing
if (!file_exists($configPath)) {
  file_put_contents($configPath, json_encode($defaultConfig, JSON_PRETTY_PRINT));
  die("⚠️ Configuration file 'config.json' was created. Please update it with your project values.");
}

// 5️⃣ Basic checks
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

// 6️⃣ Backwards compatibility: merge legacy admin_token
if (!isset($config['api_keys'])) {
  $config['api_keys'] = [
    "admin" => $config['admin_token'] ?? "change_this_token",
    "frontend" => "change_this_frontend_key"
  ];
}

// 7️⃣ Developer placeholder warnings
$warnings = [];
if ($config['db']['user'] === 'root') $warnings[] = "⚠️ DB user is still 'root'";
if ($config['db']['pass'] === '') $warnings[] = "⚠️ DB password is empty";
if (str_starts_with($config['api_keys']['admin'], 'change_this')) $warnings[] = "⚠️ Admin API key is still a placeholder";
if (str_starts_with($config['api_keys']['frontend'], 'change_this')) $warnings[] = "⚠️ Frontend API key is still a placeholder";

if (!empty($warnings)) {
  echo implode("\n", $warnings) . "\n";
}

// 8️⃣ Safe constant definitions
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

// 9️⃣ Expose full config globally
$GLOBALS['CREDENTIALS'] = $config;

// ✅ Return config array for use in init.php
return $config;

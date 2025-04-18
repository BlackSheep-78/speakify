<?php

// =============================================================================
// 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
// =============================================================================
// File: /backend/core/ConfigLoader.php
// Project: Speakify
//
// Description:
// Loads and validates configuration from `config.json` in the project root.
// Falls back to `backend/templates/config.template.json` if missing.
//
// 📜 Responsibilities:
//  1. Expect BASEPATH to be defined in the caller (typically init.php)
//  2. Locate `config.json` and template
//  3. Create `config.json` if missing (based on template)
//  4. Validate and parse the config contents as JSON
//  5. Check for insecure placeholder values and emit warnings
//  6. Provide safe backwards compatibility for legacy keys
//  7. Define global constants for backend use
//  8. Assign $GLOBALS['CREDENTIALS']
//  9. Return the full config array
// =============================================================================

class ConfigLoader
{
  const CONFIG_PATH = BASEPATH . '/backend/secrets/config.json';
  const TEMPLATE_PATH = BASEPATH . '/backend/templates/config.template.json';

  public static function load(): array
  {
    // 3⃣ Load default config from template
    $defaultConfig = [];
    if (file_exists(self::TEMPLATE_PATH)) {
      $json = file_get_contents(self::TEMPLATE_PATH);
      $defaultConfig = json_decode($json, true) ?? [];
    }

    // [4] Create config.json if missing
    if (!file_exists(self::CONFIG_PATH)) 
    {
        // Update _meta.file to reflect actual config location (relative to project root)
        $defaultConfig['_meta']['file'] = str_replace(BASEPATH, '', self::CONFIG_PATH);
        
        file_put_contents(self::CONFIG_PATH, json_encode($defaultConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        die("⚠️ Configuration file created at '/backend/secrets/config.json'. Please fill it out and set \"template\": false.");
    }
  
    // 5⃣ Basic checks and loading
    if (!is_readable(self::CONFIG_PATH)) {
      die("❌ Configuration file exists but is not readable: " . self::CONFIG_PATH);
    }

    $json = file_get_contents(self::CONFIG_PATH);
    if ($json === false) {
      die("❌ Failed to read config.json from: " . self::CONFIG_PATH);
    }

    $config = json_decode($json, true);
    if (!is_array($config)) {
      die("❌ Failed to parse 'config.json'. Please check for JSON syntax errors.");
    }

    // 6⃣ Backward compatibility for legacy keys
    if (!isset($config['api_keys'])) {
      $config['api_keys'] = [
        'admin' => $config['admin_token'] ?? 'change_this_token',
        'frontend' => 'change_this_frontend_key'
      ];
    }

    // 7⃣ Developer warnings
    $warnings = [];
    if (($config['db']['user'] ?? '') === 'root') $warnings[] = "⚠️ DB user is still 'root'";
    if (($config['db']['pass'] ?? '') === '') $warnings[] = "⚠️ DB password is empty";
    if (str_starts_with($config['api_keys']['admin'], 'change_this')) $warnings[] = "⚠️ Admin API key is still a placeholder";
    if (str_starts_with($config['api_keys']['frontend'], 'change_this')) $warnings[] = "⚠️ Frontend API key is still a placeholder";
    if (!empty($warnings)) error_log("[ConfigLoader] " . implode(' | ', $warnings));

    // 8⃣ Define global constants
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

    // 9⃣ Expose globally
    $GLOBALS['CREDENTIALS'] = $config;

    // ✅ Return config
    return $config;
  }
}

<?php
// backend/config.php

// Set timezone
date_default_timezone_set('Europe/London');

// Define base paths
if (!defined('ROOT')) {
    define('ROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
}
define('BASEPATH', __DIR__);
define('CLASSES', BASEPATH . DIRECTORY_SEPARATOR . 'classes');

// Path to global JSON config
$configFile = ROOT . DIRECTORY_SEPARATOR . 'config.json';

// If missing, generate a blank template
if (!file_exists($configFile)) {
    $defaultTemplate = [
        "env" => "development",
        "debug" => true,
        "db" => [
            "host" => "127.0.0.1",
            "name" => "speakify",
            "user" => "root",
            "pass" => ""
        ],
        "admin_token" => "change_this_token"
    ];

    file_put_contents($configFile, json_encode($defaultTemplate, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    die("⚠️ Configuration file created at: config.json\nPlease fill in your settings and reload.");
}

// Try to read the file
$raw = file_get_contents($configFile);
$configData = json_decode($raw, true);

// Handle invalid format
if (!$configData || !isset($configData['db'])) {
    die("❌ Invalid config format in config.json. Please fix or delete to regenerate.");
}

// Return flattened config array
return [
    'env'           => $configData['env'] ?? 'production',
    'debug'         => $configData['debug'] ?? false,
    'admin_token'   => $configData['admin_token'] ?? null,

    'db_host'       => $configData['db']['host'],
    'db_name'       => $configData['db']['name'],
    'db_user'       => $configData['db']['user'],
    'db_pass'       => $configData['db']['pass'],
];

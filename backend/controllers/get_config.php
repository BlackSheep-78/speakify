<?php

// =============================================================================
// 📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
// =============================================================================
// File: backend/controllers/get_config.php
// Project: Speakify
//
// Description:
// API endpoint to expose safe configuration settings to the frontend.
// Typically used to load `base_url`, `env`, and `debug` at runtime.
//
// 🧾 Responsibilities:
//  1. Load the global configuration from $GLOBALS['CREDENTIALS']
//  2. Return a safe subset of values (e.g. base_url, env, debug)
//  3. Avoid exposing sensitive or internal config keys
// =============================================================================

$config = $GLOBALS['CREDENTIALS'] ?? [];

echo json_encode([
  'success' => true,
  'env' => $config['env'] ?? 'production',
  'debug' => $config['debug'] ?? false,
  'base_url' => $config['base_url'] ?? '',
]);

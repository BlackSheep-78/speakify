<?php
// =============================================================================
// Project: Speakify
// File: backend/controllers/get_config.php
// Description: Exposes safe runtime config (env, debug, base_url) to frontend.
// =============================================================================

$config = $GLOBALS['CREDENTIALS'] ?? [];

echo json_encode([
  'success'  => true,
  'env'      => $config['env'] ?? 'production',
  'debug'    => $config['debug'] ?? false,
  'base_url' => $config['base_url'] ?? '',
  'token'    => $token
]);
<?php
// ============================================================================
// File: speakify/backend/actions/get_sentences.php
// Description:
//     Entry point for serving sentence data. This file expects a valid
//     authenticated session token and returns structured sentence results
//     based on the logic inside `php/get_sentences.php`.
//
// Assumptions:
//     - $config and BASEPATH are already defined in the calling context.
//     - This file is executed via /public/api/index.php?action=get_sentences
//
// Responsibilities:
//     - Initializes PDO if not already available
//     - Authenticates the user (via token)
//     - Delegates to php/get_sentences.php for core data logic
//     - Returns JSON responses with proper HTTP codes
// ============================================================================

try {
    // ✅ Ensure $config is present
    if (!isset($config) || !defined('BASEPATH')) {
        http_response_code(500);
        echo json_encode(['error' => 'Environment not initialized']);
        exit;
    }

    // ✅ Setup PDO if not already available
    if (!isset($pdo)) {
        $pdo = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
            $config['db_user'],
            $config['db_pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    // ✅ Auth check (sets $auth_user_id or exits with error)
    require_once BASEPATH . '/utils/auth.php';

    // ✅ Fetch and return sentence data
    require_once BASEPATH . '/php/get_sentences.php';

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection error',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Unexpected error in get_sentences entry point',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
}

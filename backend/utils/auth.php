<?php
// ============================================================================
// File: speakify/backend/utils/auth.php
// Description:
//     Validates the session token using SessionManager. Sets $auth_user_id
//     (null = anonymous user). Updates last_activity on each request.
//     Requires $config and $pdo or loads them if missing.
// ============================================================================

$config = $config ?? require_once __DIR__ . '/../config.php';

try {
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

    // ✅ Load SessionManager class
    require_once BASEPATH . '/classes/SessionManager.php';
    $sessionManager = new SessionManager($pdo, $config);

    // ✅ Extract token from request
    $token = $_GET['token'] ?? $_POST['token'] ?? '';
    if (!$token) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Missing session token',
            'details' => $config['debug'] ? 'Token must be passed via GET or POST.' : null
        ]);
        exit;
    }

    // ✅ Validate and touch session
    $session = $sessionManager->validate($token);
    if (!$session) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Invalid or expired session',
            'details' => $config['debug'] ? "Token not found or expired: $token" : null
        ]);
        exit;
    }

    // ✅ Set auth user ID
    $auth_user_id = $session['user_id'];

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Auth check failed (DB)',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Auth check failed (Unexpected)',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
}

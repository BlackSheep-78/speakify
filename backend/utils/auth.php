<?php
// =============================================================================
// File: utils/auth.php
// Description: Validates a session token and sets $auth_user_id.
// Requires $config and $pdo to be available in the global scope.
// =============================================================================

if (!isset($pdo)) {
    // Set up PDO if it's not already defined
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

$token = $_GET['token'] ?? $_POST['token'] ?? '';

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Missing session token']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM `sessions` WHERE `token` = :token AND `expires_at` > NOW()");
    $stmt->execute(['token' => $token]);
    $session = $stmt->fetch();

    if (!$session) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid or expired session']);
        exit;
    }

    // Set this global in your action to use
    $auth_user_id = $session['user_id'];
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Auth check failed', 'details' => $e->getMessage()]);
    exit;
}

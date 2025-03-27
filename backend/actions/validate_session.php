<?php
// File: speakify/backend/actions/validate_session.php

require_once BASEPATH . '/classes/SessionManager.php';

$sm = new SessionManager($pdo, $config);
$token = $_GET['token'] ?? null;

$session = $sm->validateToken($token);

if (!$session) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired session']);
    exit;
}

echo json_encode([
    'status' => 'valid',
    'user_id' => $session['user_id'],
    'last_activity' => $session['last_activity']
]);

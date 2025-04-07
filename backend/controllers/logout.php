<?php
// =============================================================================
// File: actions/logout.php
// Description: Unlinks user_id from a session, but keeps session active
// =============================================================================

require_once BASEPATH . '/backend/classes/SessionManager.php';

Logger::info("logout.php called",__FILE__,__LINE__);

$token = $_GET['token'] ?? '';

if (!$token) {

  Logger::info("exiting #4!");
  http_response_code(400);
  echo json_encode(['error' => 'Missing token']);
  exit;
}

try {
  $result = SessionManager::logout($token);

  Logger::info("here #4!");


  echo json_encode($result);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}

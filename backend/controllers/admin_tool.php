<?php
/*
  ================================================================================
  📁 File: controllers/admin_tools.php
  📌 Project: Speakify
  🧰 Type: API Controller
  📚 Description:
     - Handles backend admin-only API actions.
     - Each action is triggered by the `tool` GET param.
  ================================================================================
*/

Logger::debug("admin_key: " . ($_GET['admin_key'] ?? 'none'));
Logger::debug("token: " . ($_GET['token'] ?? 'none'));
Logger::debug("final used token: " . $token);

$token = $_GET['admin_key'] ?? $_GET['token'] ?? null;

if (!AdminService::isAdmin($token)) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}
    

$tool = $_GET['tool'] ?? '';

switch ($tool) {
    case 'generate_file_structure':
        echo json_encode(AdminService::generateFileStructure());
        break;

    // 🧩 Add more admin tools here...

    default:
        echo json_encode(['error' => 'Unknown admin tool']);
        break;
}

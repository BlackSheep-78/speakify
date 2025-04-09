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

Logger::info("admin tool");

require_once dirname(__DIR__) . '/classes/AdminService.php';

if (!AdminService::isAdmin()) {
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

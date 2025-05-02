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

$token = Input::get('token', 'token', null);
$tool  = Input::get('tool', 'string', '');

if (!AdminService::isAdmin($token)) 
{
    echo json_encode(['error' => 'Access denied']);
    exit;
}
    



switch ($tool) 
{
    case 'generate_file_structure':
        echo json_encode(AdminService::generateFileStructure());
        break;

    // 🧩 Add more admin tools here...

    default:
        echo json_encode(['error' => 'Unknown admin tool']);
        break;
}

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

global $database;

$token = Input::get('token', 'token', null);
$tool  = Input::get('tool', 'string', '');

if (!AdminService::isAdmin($token,['db'=>$database])) 
{
    output(['error' => 'Access denied',['code'=>'ERROR_1534']]);
    exit;
}
    

switch ($tool) 
{
    case 'generate_file_structure':
        output(AdminService::generateFileStructure());
        break;

    // 🧩 Add more admin tools here...

    default:
        output(['error' => 'Unknown admin tool']);
        break;
}

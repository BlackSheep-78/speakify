<?php
// File: /speakify/backend/controllers/export_mysql_schema.php

require_once BASEPATH . '/backend/core/ConfigLoader.php';
require_once BASEPATH . '/backend/models/Database.php'; // Your PDO wrapper

header('Content-Type: application/json');

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $schema = [];

    foreach ($tables as $table) {
        $res = $db->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $schema[$table] = $res['Create Table'] ?? 'N/A';
    }

    echo json_encode([
        'success' => true,
        'schema' => $schema
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}


$db = Database::init();
$db->query("SHOW TABLES"); or $db->file("/sql/admim/show_tables.sql");
$tables = $db->result([possibility to throw options in here]);

so that script would be basicly :

$db = Database::init();
$db->file("/sql/admim/show_tables.sql");
$tables = $db->result([possibility to throw options in here]);

i like this kind of abstraction

the method $db->replace() or another name that you think bzerrst
would be used to replace vars on the query while sanitazing the input like 

$db->replace("{USER:ID}",$user_id,"i") where "i" means integer 
$db->replace("{USER:MAIL}",$user_mail,"e") where "e" means an email adress
etc.. etc... do you get my point ?

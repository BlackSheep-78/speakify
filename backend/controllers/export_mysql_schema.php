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

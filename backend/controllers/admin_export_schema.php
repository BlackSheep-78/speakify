<?php
// =============================================================================
// 📄 File: /backend/controllers/admin_export_schema.php
// 🎯 Purpose: Export full MySQL schema using SHOW CREATE TABLE
// 🔐 Access: Admin-only (must be validated upstream)
// =============================================================================

header('Content-Type: application/json');

try {
    $db = Database::init();

    $tables = $db->query("SHOW TABLES")->result(['fetch' => 'column']);
    $schema = [];

    foreach ($tables as $table) {
        $result = $db->query("SHOW CREATE TABLE `$table`")->result(['fetch' => 'assoc']);
        $schema[$table] = $result[0]['Create Table'] ?? 'N/A';
    }

    echo json_encode([
        'success' => true,
        'schema' => $schema
    ]);
} catch (Throwable $e) {
    Logger::log('ERROR', 'Schema export failed: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'error' => 'Schema export failed',
        'details' => defined('DEBUG') && DEBUG ? $e->getMessage() : null
    ]);
}

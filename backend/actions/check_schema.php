<?php
// =============================================================================
// File: actions/check_schema.php
// Description:
//   Dynamically checks all .sql files in /sql/schema to determine required tables.
//   Verifies if each exists in the database and restores missing ones if possible.
// =============================================================================

try {
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
        $config['db_user'],
        $config['db_pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $schemaDir = BASEPATH . '/sql/schema';
    $restored = [];
    $missingTables = [];
    $existingTables = [];

    // STEP 1: Get expected table names from .sql filenames
    $requiredTables = [];
    foreach (glob("$schemaDir/*.sql") as $filePath) {
        $filename = basename($filePath, '.sql');
        if (preg_match('/^[a-zA-Z0-9_]+$/', $filename)) {
            $requiredTables[] = $filename;
        }
    }

    // STEP 2: Get currently existing tables in DB
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $existingTables[] = $row[0];
    }

    // STEP 3: Compare and collect missing
    foreach ($requiredTables as $table) {
        if (!in_array($table, $existingTables)) {
            $missingTables[] = $table;
        }
    }

    // STEP 4: Restore missing tables from corresponding .sql files
    foreach ($missingTables as $table) {
        $sqlFile = "$schemaDir/$table.sql";
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            try {
                $pdo->exec($sql);
                $restored[] = $table;
            } catch (PDOException $e) {
                echo json_encode([
                    'error' => "Failed to restore table '$table'",
                    'details' => $e->getMessage()
                ]);
                exit;
            }
        }
    }

    echo json_encode([
        'status' => empty($missingTables) ? 'ok' : (empty($restored) ? 'incomplete' : 'restored'),
        'required' => $requiredTables,
        'existing' => $existingTables,
        'missing' => $missingTables,
        'restored' => $restored
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection failed',
        'details' => $e->getMessage()
    ]);
    exit;
}

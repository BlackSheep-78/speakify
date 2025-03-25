<?php
// backend/php/build_database.php

$host = '127.0.0.1';
$dbname = 'speakify';
$user = 'root';
$pass = ''; // Update if needed

$schemaDir = __DIR__ . '/../../sql/schema/';
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

// Optional: disable foreign key checks temporarily
$pdo->exec("SET FOREIGN_KEY_CHECKS=0;");

// Get all .sql files
$sqlFiles = glob($schemaDir . '*.sql');
sort($sqlFiles); // Ensure order if needed

foreach ($sqlFiles as $file) {
    $sql = file_get_contents($file);
    echo "Running: " . basename($file) . "\n";

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        echo "❌ Error in " . basename($file) . ": " . $e->getMessage() . "\n";
    }
}

// Re-enable foreign key checks
$pdo->exec("SET FOREIGN_KEY_CHECKS=1;");

echo "✅ Database build complete.";

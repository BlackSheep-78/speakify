<?php
/**
 * Test MySQL connection using Speakify config
 * Save this file as: test_mysql_connection.php
 */

// Config values (hardcoded here for testing)
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'speakify',
    'user' => 'speakify_user',
    'pass' => 'g_L!/.)Z*=jz8Bv7;nw6+W',
];

// Test connection using PDO
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "✅ MySQL connection successful!\n";

    // Optional: run a test query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📦 Tables in the database:\n";
    foreach ($tables as $table) {
        echo " - $table\n";
    }

} catch (PDOException $e) {
    echo "❌ MySQL connection failed: " . $e->getMessage() . "\n";
}

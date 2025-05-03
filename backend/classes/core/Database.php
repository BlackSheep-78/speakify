<?php
// 📁 File: backend/core/Database.php
// 🏦 Project: Speakify
// 🧠 Description: Centralized PDO-based database class with clean SQL file loading,
// variable replacement, query execution, and optional raw PDO access.

class Database {
    private static $instance = null;
    private PDO $pdo;
    private string $query = '';
    private array $bindings = []; // ✨ Stores [':TOKEN' => [value, type]]
    private ?string $rawSql = null;   // 🔍 Tracks raw query until result()

    /**
     * 🔁 Constructor (private): Establish PDO connection using config.json.
     */
    private function __construct() 
    {
        $config = ConfigLoader::load();

        $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    // Static init: Return singleton instance of Database.
    public static function init(): self 
    {
        if (!self::$instance) {
            try {
                self::$instance = new self();
            } catch (PDOException $e) {
                Logger::error("❌ Database connection failed: " . $e->getMessage());
                http_response_code(500);
                output([
                    'error' => 'Database connection failed',
                    'details' => defined('DEBUG') && DEBUG ? $e->getMessage() : 'See server logs'
                ]);
                exit;
            }
        }
        return self::$instance;
    }

    /**
     * 📄 Load SQL file from relative path.
     */
    public function file(string $relativePath): self 
    {
        $path = BASEPATH . '/backend/sql' . $relativePath;
        if (!file_exists($path)) {
            throw new Exception("SQL file not found: $path");
        }
        $this->rawSql = file_get_contents($path);
        return $this;
    }

    /**
     * 🔗 Direct query injection.
     */
    public function query(string $sql): self 
    {
        $this->rawSql = $sql;
        return $this;
    }

    /**
     * 🧬 Register a token and value to be bound into the statement.
     */
    public function replace(string $token, $value, string $type = 's'): self 
    {
        $this->bindings[$token] = [$value, $type];
        return $this;
    }

    /**
     * 📤 Execute query with bound parameters and return results.
     * Options: 'fetch' => 'assoc' | 'column' | 'object'
     */
    /*
    public function result(array $options = []): mixed 
    {
        if (!$this->rawSql) {
            throw new RuntimeException("No SQL query set.");
        }

        $stmt = $this->pdo->prepare($this->rawSql);

        foreach ($this->bindings as $token => [$value, $type]) {
            $stmt->bindValue($token, $value, $this->mapPDOType($type));
        }

        //error_log('[SQL] ' . $this->rawSql);
        //error_log('[BINDINGS] ' . print_r($this->bindings, true));

        $stmt->execute();

        // Reset state after execution
        $this->rawSql = null;
        $this->bindings = [];

        return match ($options['fetch'] ?? 'assoc') {
            'column' => $stmt->fetchAll(PDO::FETCH_COLUMN),
            'object' => $stmt->fetchAll(PDO::FETCH_OBJ),
            default => $stmt->fetchAll(PDO::FETCH_ASSOC),
        };
    }*/

    public function result(array $options = []): mixed
{
    if (!$this->rawSql) {
        throw new RuntimeException("No SQL query set.");
    }

    $stmt = $this->pdo->prepare($this->rawSql);

    foreach ($this->bindings as $token => [$value, $type]) {
        $stmt->bindValue($token, $value, $this->mapPDOType($type));
    }

    $stmt->execute();

    $this->rawSql = null;  // Reset state after execution
    $this->bindings = [];

    // Fetch the result based on 'fetch' option
    return match ($options['fetch'] ?? 'assoc') {
        'column' => $stmt->fetchAll(PDO::FETCH_COLUMN), 
        'object' => $stmt->fetchAll(PDO::FETCH_OBJ),
        'row' => $stmt->fetch(PDO::FETCH_ASSOC), // Only fetch a single row here
        default => $stmt->fetchAll(PDO::FETCH_ASSOC),
    };
}

    /**
     * 🔒 Map custom type codes to PDO::PARAM constants.
     */
    private function mapPDOType(string $type): int {
        return match ($type) {
            'i' => PDO::PARAM_INT,
            'b' => PDO::PARAM_BOOL,
            'e', 's' => PDO::PARAM_STR,
            default => PDO::PARAM_STR,
        };
    }

    /**
     * 🪪 Get raw SQL query string for debug.
     */
    public function raw(): string {
        return $this->rawSql ?? $this->query;
    }

    /**
     * 🔌 Access raw PDO connection.
     */
    public function getPDO(): PDO {
        return $this->pdo;
    }
}

<?php
// 📁 File: backend/core/Database.php
// 🏦 Project: Speakify
// 🧠 Description: Centralized PDO-based database class with clean SQL file loading,
// variable replacement, query execution, and optional raw PDO access.

class Database {
    private static $instance = null;
    private PDO $pdo;
    private string $query = '';
    private array $bindings = [];
    private array $replacements = []; // 🪨 New: store token replacements for raw injection
    private ?string $rawSql = null;   // 🔍 New: track raw query until result()

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
                Logger::error("❌ Database connection failed: " . $e->getMessage(), __FILE__, __LINE__);
                http_response_code(500);
                echo json_encode([
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
     * 🧬 Replace token in query with sanitized value.
     */
    public function replace(string $token, $value, string $type = 's'): self 
    {
        $this->replacements[$token] = $this->sanitize($value, $type);
        return $this;
    }

    /**
     * 🧡 Internal sanitize logic (type-based).
     */
    protected function sanitize($value, string $type): string 
    {
        return match ($type) {
            'i' => (string)(int) $value,
            'f' => (string)(float) $value,
            'b' => $value ? '1' : '0',
            'e' => filter_var($value, FILTER_VALIDATE_EMAIL) ? $this->pdo->quote($value) : "''",
            's' => $this->pdo->quote($value),
            'raw' => $value,
            default => throw new InvalidArgumentException("Unknown sanitize type: $type"),
        };
    }

    /**
     * 📤 Execute query and return result with custom fetch options.
     * Options: 'fetch' => 'assoc' | 'column' | 'object'
     */
    public function result(array $options = []): mixed {
        if (!$this->rawSql) {
            throw new RuntimeException("No SQL query set.");
        }

        $sql = $this->rawSql;
        foreach ($this->replacements as $token => $value) {
            $sql = str_replace($token, $value, $sql);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Reset
        $this->rawSql = null;
        $this->replacements = [];

        return match ($options['fetch'] ?? 'assoc') {
            'column' => $stmt->fetchAll(PDO::FETCH_COLUMN),
            'object' => $stmt->fetchAll(PDO::FETCH_OBJ),
            default => $stmt->fetchAll(PDO::FETCH_ASSOC),
        };
    }

    /**
     * 🔒 Internal: Map custom type codes to PDO::PARAM constants.
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
     * 🪪 Get the raw SQL query string (for debugging).
     */
    public function raw(): string {
        return $this->rawSql ?? $this->query;
    }

    /**
     * 🔌 Direct access to raw PDO instance (use sparingly).
     */
    public function getPDO(): PDO {
        return $this->pdo;
    }
}

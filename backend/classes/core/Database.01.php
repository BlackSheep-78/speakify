<?php
// 📁 File: backend/core/Database.php
// 📦 Project: Speakify
// 🧠 Description: Centralized PDO-based database class with clean SQL file loading,
// variable replacement, query execution, and optional raw PDO access.

class Database {
    private static $instance = null;
    private PDO $pdo;
    private string $query = '';
    private array $bindings = [];

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
        $this->query = file_get_contents($path);
        $this->bindings = []; // 🧹 Clear old bindings before reusing
        return $this;
    }

    /**
     * 🧬 Replace tokens in query with sanitized values.
     * @param string $token  Token format: {PLACEHOLDER:TYPE}
     * @param mixed  $value  Value to inject
     * @param string $type   'i'=int, 's'=string, 'e'=email, 'b'=bool
     */
    public function replace(string $token, $value, string $type = 's'): self 
    {
        $placeholder = ':' . strtoupper(trim($token, '{}'));
        $this->bindings[$placeholder] = [$value, $type];
        return $this;
    }

    /**
     * 📤 Execute query and return result with custom fetch options.
     * Options: 'fetch' => 'assoc' | 'column' | 'object'
     */
    public function result(array $options = []): mixed {
        $stmt = $this->pdo->prepare($this->query);

        foreach ($this->bindings as $key => [$value, $type]) {
            $stmt->bindValue($key, $value, $this->mapPDOType($type));
        }

        //error_log('[SQL] ' . $this->query);
        //error_log('[Bindings] ' . print_r($this->bindings, true));

        $stmt->execute();

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
        return $this->query;
    }

    /**
     * 🔌 Direct access to raw PDO instance (use sparingly).
     */
    public function getPDO(): PDO {
        return $this->pdo;
    }
}

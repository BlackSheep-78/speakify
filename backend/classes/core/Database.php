<?php
/*
    file: /speakify/backend/classes/Database.php
*/

class Database extends Utilities
{
    private static $instance = null;
    private static $connection = null;  // Make the connection static
    public $query = null;
    public $replace_list = [];
    public $results = [];
    public $total = null;
    public $error = null;

    // ✅ Private constructor for singleton
    private function __construct()
    {
        global $pdo;

        if ($pdo instanceof PDO) {
            self::$connection = $pdo;  // Store the connection statically
        } else {
            Logger::log("❌ PDO not found");
        }
    }

    // ✅ Singleton accessor
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // ✅ Static method to access the connection
    public static function getConnection()
    {
        if (!self::$connection) {
            Logger::log("❌ No valid PDO connection");
            return null;
        }
        return self::$connection;
    }

    public function connect()
    {
        if (!self::$connection) {
            Logger::log("❌ No PDO connection available");
        }
        return $this;
    }

    public function file($file)
    {
        $this->query = file_get_contents(BASEPATH . "/sql/" . $file);
        return $this;
    }

    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    public function replace($search, $replace, $validation)
    {
        $this->replace_list[] = [
            'search' => $search,
            'replace' => $replace,
            'validation' => $validation
        ];
    }

    public function result($ammount = null)
    {
        if (!self::$connection) {
            trigger_error("Not connected to PDO database");
            return $this;
        }

        $this->replace_execute();

        try {
            $stmt = self::$connection->prepare($this->query);
            $stmt->execute();

            if ($ammount == 1) {
                $this->results = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $this->results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if ($ammount !== 1) {
                $stmt = self::$connection->query("SELECT FOUND_ROWS()");
                $this->total = $stmt->fetchColumn();
            }

        } catch (PDOException $e) {
            Logger::log("PDO Error: " . $e->getMessage());
            $this->error = $e->getMessage();
        }

        return $this->results;
    }

    private function replace_execute()
    {
        foreach ($this->replace_list as $value) {
            $search     = $value['search'];
            $replace    = $value['replace'];
            $validation = $value['validation'];

            switch ($validation) {
                case "s":
                    $this->query = str_replace("{" . $search . "}", self::$connection->quote($replace), $this->query);
                    break;
                case "i":
                    $this->query = str_replace("{" . $search . "}", $replace, $this->query);
                    break;
            }
        }

        return $this;
    }
}

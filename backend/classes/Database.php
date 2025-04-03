<?php
/*
    file: /speakify/backend/classes/Database.php
*/
require_once BASEPATH . "/backend/classes/Utilities.php";

class Database extends Utilities
{
    private static $instance = null;
    public $connection = null;
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
            $this->connection = $pdo;
        } else {
            error_log("❌ PDO not found");
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

    public function connect()
    {
        if (!$this->connection) {
            error_log("❌ No PDO connection available");
        }
        return $this;
    }

    public function getConnection()
    {
        if (!$this->connection) {
            error_log("❌ No valid PDO connection");
            return null;
        }
        return $this->connection;
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
        if (!$this->connection) {
            trigger_error("Not connected to PDO database");
            return $this;
        }

        $this->replace_execute();

        try {
            $stmt = $this->connection->prepare($this->query);
            $stmt->execute();

            if ($ammount == 1) {
                $this->results = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $this->results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if ($ammount !== 1) {
                $stmt = $this->connection->query("SELECT FOUND_ROWS()");
                $this->total = $stmt->fetchColumn();
            }

        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
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
                    $this->query = str_replace("{" . $search . "}", $this->connection->quote($replace), $this->query);
                    break;
                case "i":
                    $this->query = str_replace("{" . $search . "}", $replace, $this->query);
                    break;
            }
        }

        return $this;
    }
}

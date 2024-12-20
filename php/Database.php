<?php

    class Database 
    {

        private $host = 'localhost';       // Database host (e.g., localhost or an IP address)
        private $dbname = 'Translate'; // Name of the database
        private $username = 'root'; // Database username
        private $password = ''; // Database password

        private $connection = 'NULL';

        public function __construct() 
        {
            $this->connect();
        }

        public function __destruct() 
        {
            // Close the connection when done
            $this->connection->close();
        }

        private function connect()
        {
            // Create a connection to MySQL using MySQLi
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            // Check if the connection is successful
            if ($this->connection->connect_error) 
            {
                // If the connection fails, display the error message
                die("Connection failed: " . $this->connection->connect_error);
            } 
            else 
            {
                // If the connection is successful, display this message
                //echo "Connected successfully to the database!";
            } 
        }

        public function query($sql)
        {
            $data = [];

            $result = mysqli_query($this->connection, $sql);

            if ($result) 
            {
                // Fetch the result as an associative array
                while ($row = mysqli_fetch_assoc($result)) 
                {
                   $data[] = $row;
                }
            } 
            else 
            {
                echo "Error executing query: " . mysqli_error($conn);
            }

            return $data;
        }
    }
?>
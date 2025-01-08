<?php

    class Database 
    {

        private $host          = 'localhost'; 
        private $database_name = NULL;  
        private $username      = 'root';     
        private $password      = '';         

        private $connection    = NULL;
        private $query         = NULL;
        private $file          = NULL;
        private $variables     = [];

        public function __construct($database_name = NULL) 
        {
            $this->database_name = $database_name;

            if($this->database_name === NULL)
            {
                trigger_error(" NO DATABASE SELECTED ");
                return NULL;
                
            } 

            $this->connect();
            return $this;
        }

        private function connect()
        {            
            if($this->database_name === NULL)
            {
                trigger_error(" NO DATABASE SELECTED ");
                return NULL;
            } 

            // Create a connection to MySQL using MySQLi
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database_name);

            // Check if the connection is successful
            if ($this->connection->connect_error) 
            {
                // If the connection fails, display the error message
                trigger_error("Connection failed: " . $this->connection->connect_error);
            } 
            else 
            {
                // If the connection is successful, display this message
                //echo "Connected successfully to the database!";
            } 
        }

        public function replaceAallVariables()
        {
            foreach($this->variables as $key => $value)
            {
                $this->sql = str_replace("{".$key."}", $value, $this->sql);
            }
        }

        public function replace($key,$value)
        {
            $this->variables[$key] = $value;
        }

        public function file($file)
        {
            $this->file = BASE_PATH."\\sql\\".$file;
            $this->sql = file_get_contents($this->file);
        }

        public function query($sql)
        {
            $this->sql = $sql;
        }

        public function result()
        {
            if($this->database_name === NULL)
            {
                trigger_error(" NO DATABASE SELECTED ");
                return NULL;
            } 

            $this->replaceAallVariables();

            $data = [];

            print "<pre>".$this->sql."</pre>";



            $result = mysqli_query($this->connection, $this->sql);

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

        public function __destruct() 
        {
            if($this->database_name === NULL)
            {
                trigger_error(" NO DATABASE SELECTED ");
                return NULL;
            } 

            // Close the connection when done
            $this->connection->close();
        }
    }
?>
<?php
    /*
        file: /speakify/backend/classes/Database.php
    */
    require_once BASEPATH."/classes/Utilities.php";
    
	class Database extends Utilities
	{
        //sq_mode="STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
        
        public $host             = 'localhost';
        public $user             = 'root';
        public $password         = '';
        public $database         = 'translate';
        
        public $connection       = NULL;
        
        public $file_            = NULL;
        public $query            = NULL;

        public $replace_list     = [];
        
        public $results          = [];
        public $total            = NULL;
        
        public $error            = NULL;
        
        function __construct()
        {
        
        }
        
		function connect()
		{
            /*
            $this->host       = $credentials['host'];
            $this->user       = $credentials['user'];
            $this->password   = $credentials['password'];
            $this->database   = $credentials['database'];
            */

			$this->connection = mysqli_init();
            
			mysqli_options($this->connection, MYSQLI_OPT_LOCAL_INFILE, true);
			mysqli_real_connect($this->connection,$this->host,$this->user,$this->password ,$this->database);	
            
            return $this;
		}      
        
		public function file($file)
		{
				$this->query = file_get_contents(BASEPATH."/sql/".$file);	
                
                return $this;
		}
        
		public function query($query)
		{
                $this->file_ = 'none';
				$this->query = $query;	
                
                return $this;
		}

        public function replace($search,$replace,$validation)
        {
            $this->replace_list[] = ['search' => $search,'replace' => $replace, 'validation' => $validation];
        }

		public function result($ammount = NULL)
		{            
            if(!$this->connection)
            {
                trigger_error("Not connected to database");
                return $this;
            }

            $this->replace_execute();

			if(mysqli_multi_query($this->connection,$this->query))
            {
                do
                {
                    if($result = mysqli_store_result($this->connection)) 
                    {
                        $this->results = [];
                        
                        while ($row=mysqli_fetch_assoc($result))
                        {
                            if($ammount == 1)
                            {
                                $this->results = $row;
                                break;
                            }
                            else
                            {
                                $this->results[] = $row;
                            }
                        }
                        
                        if($total = mysqli_query($this->connection,"SELECT FOUND_ROWS()"))
                        {
                            $row = mysqli_fetch_array($total); 
                            $this->total = $row['FOUND_ROWS()'];
                            
                            mysqli_free_result($total);
                        }
                        
                        mysqli_free_result($result);
                        
                    }
                    
                    if($ammount == 1) { break; }
                }
                while (mysqli_more_results($this->connection) && mysqli_next_result($this->connection));   
                

            }
            
            $errno = mysqli_errno($this->connection);
            
            
            if($errno > 0 and $this->log_ == "file")
            {
                $description = mysqli_error($this->connection);
                mysql_error_handler_to_file($errno, $description, 0,$this->file_,$this->query);
            }
            else if($errno > 0)
            {
                $description = mysqli_error($this->connection);
                mysql_error_handler($errno, $description, 0,$this->file_,$this->query);
            }
            
            mysqli_close($this->connection);
            return $this->results;
		}
        
        private function replace_execute()
        {
			//trigger_error("replace ".$search." ".$replace." ".$validation." ".$this->query);

            foreach($this->replace_list as $key => $value)
            {
                $search     = $value['search'];
                $replace    = $value['replace'];
                $validation = $value['validation'];

                switch($validation)
                {
                    case "s":
                        $this->query = str_replace("{".$search."}",mysqli_real_escape_string($this->connection,$replace),$this->query);
                    break;
                    case "i":
                        $this->query = str_replace("{".$search."}",$replace,$this->query);
                    break;
                }
            }
		
            return $this;
        }
	}
?>

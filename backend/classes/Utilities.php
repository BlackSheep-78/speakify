<?php

    class Utilities
    {
		public $input    = NULL;
		public $internal = NULL;
		public $output   = NULL;
		
		/*
        function construct($data)
        {
            $array_data = NULL;
            
            if(is_string($data))
            {
                $array_data = json_decode($data,true);     
            } 
            else if(is_array($data))
            {
                $array_data = $data;
            }
            
            if(is_array($array_data))
            {
                foreach($array_data as $key => $value)
                {
                    $this->$key = $value;
                }
            }
        }
		*/
        
        function set($key,$value)
        {
            $self = &$this;
            
            $keys = explode("/",$key);
            $n    = count($keys);
            
            for($i = 0; $i < $n; $i++)
            {
                if($n == 1)
                {
                    if(!isset($self->{$keys[$i]}))
                    {
                        $self->{$keys[$i]} = $value;
                    }
                }
                else if($i == ($n-1))
                {
                        $self[$keys[$i]] = $value;
                }
                else if($i == 0)
                {
                    if(!isset($self->{$keys[$i]}))
                    {
                        $self->{$keys[$i]} = [];
                    }
                    
                    $self = &$self->{$keys[$i]};
                }
                else
                {
                    $self[$keys[$i]] = [];
                    $self = &$self[$keys[$i]];
                }
            }
            
            return $this;
        }
        
        function log()
        {
            $result = [];
            
            $arguments = func_get_args();
            
            foreach($arguments as $argument)
            {
                if(is_array($argument) or is_object($argument))
                {
                    $argument = print_r($argument,true);
                } 

                $result[] = $argument;    
            }
            
            trigger_error(implode(" \n\n",$result));
        }
		
		function input()
		{
			$n = func_num_args();
			
			if($n == 2)
			{
				if($this->input === NULL) { $this->input = []; }
				
				$args = func_get_args();
				
				$this->input[$args[0]] = $args[1];
				
				return $this;
			}

			if($this->input === NULL)
			{
				return 'no input';
			}
			
			return $this->input;
		}
		
		function output()
		{
			$n = func_num_args();
			
			if($n == 2)
			{
				if($this->output === NULL) { $this->output = []; }
				
				$args = func_get_args();
				
				$this->output[$args[0]] = $args[1];
				
				return $this;
			}

			if($this->output === NULL)
			{
				return 'no output';
			}
			
			return $this->output;
		}
    }
?>
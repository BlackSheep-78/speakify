<?php

    if(isset($_GET['json']) and $_GET['json']==true)
    {
        echo file_get_contents(getcwd()."/data.json");
		
        return;
    }

    print file_get_contents(getcwd()."/html/index.html");

?>
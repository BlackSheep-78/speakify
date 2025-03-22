<?php

    echo "<center>mysqltruncate.php<br><br><a href='?file=mysqltruncate.php&confirm=true'>CONFIRM</a></center>";

    require_once BASEPATH."/classes/Database.php";

    $database = 'translate';


    IF(ISSET($_GET['confirm']) && $_GET['confirm']  == true)
    {
        $db = new Database();
        $db->connect();
        $db->file("reset_database.sql");
        $rows = $db->result();

        echo "<meta http-equiv='refresh' content='0; url=?file=mysqltruncate.php'>";
    }

    

?>
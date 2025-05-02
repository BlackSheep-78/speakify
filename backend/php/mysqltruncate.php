<?php

    echo "<center>mysqltruncate.php<br><br><a href='?file=mysqltruncate.php&confirm=true'>CONFIRM</a></center>";

    require_once BASEPATH."/classes/Database.php";

    $database = 'translate';


    if (Input::get('confirm', 'bool', false))
    {
        $db = new Database();
        $db->connect();
        $db->file("reset_database.sql");
        $rows = $db->result();

        echo "<meta http-equiv='refresh' content='0; url=?file=mysqltruncate.php'>";
    }

    

?>
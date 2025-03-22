<?php

    echo "<center>mysqlschema.php</center><br><br><br>";

    require_once BASEPATH."/classes/Database.php";

    $database = 'translate';

    $db = new Database();
    $db->connect();
    $db->query("SHOW TABLES");
    $rows = $db->result();

    // Initialize output content
    $output = "-- Database Schema Export\n";
    $output .= "-- Database Name: $database\n\n";

    foreach($rows as $row)
    {
        $table = $row['Tables_in_translate'];

        $db = new Database();
        $db->connect();
        $db->replace('table',$table,'s');
        $db->query("SHOW CREATE TABLE `{table}`");
        $row = $db->result();

        $create = $row[0]['Create Table'];

        $output .= "-- Table: `$table`\n";
        $output .= $create . ";\n\n";

    }


    print "<center><textarea id='mysql_dump'>";
    print $output;
    print "</textarea></center>";

    


?>
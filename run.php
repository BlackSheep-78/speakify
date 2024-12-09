<?php
    print "<br><center>run.php</center><br><br>";

    include_once("Database.php");
    include_once("Translate.php");

    // Select all languages
    /*
    $db = new Database();
    $rows = $db->query("SELECT * FROM languages");
    print_r($rows);
    */

    // Translate 1 using google

    $translate = new Translate();
    $translate->connectToGoogleToTranslate();
?>
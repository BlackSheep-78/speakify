<?php
    print "<br><center>php/run.php</center><br><br>";

    require_once BASEPATH."/classes/Database.php";
    require_once BASEPATH."/classes/Translate.php";

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
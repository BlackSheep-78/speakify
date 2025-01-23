<?php

    require_once('config.php');
    require_once(ROOT.DIRECTORY_SEPARATOR.'engine'.DIRECTORY_SEPARATOR.'config.php');


    if(isset($_GET['json']) && $_GET['json'] == true)
    {
        if(isset($_GET['get']) && $_GET['get'] == 'sentences')
        {
            include_once "php/get_sentences_2.php";
            exit;
        }
    }

    $html = file_get_contents(getcwd()."/html/index.html");
    $menu = file_get_contents(getcwd()."/html/menu.html"); 
    $html = str_replace("{menu}",$menu,$html);

    if(!isset($_GET['file']))
    {
        $app = file_get_contents(getcwd()."/html/app.html"); 
        $html = str_replace("{content}",$app,$html);
    }
    else if(isset($_GET['file']))
    {
        $file = $_GET['file'];

        ob_start();
        include_once("php/".$file);
        $output = ob_get_clean();
        $html = str_replace("{content}",$output,$html);
    }

    print $html;




?>
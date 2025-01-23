<?php
    date_default_timezone_set('Europe/London');

    if(!defined('ROOT'))
    {
        define('ROOT',realpath(__DIR__.DIRECTORY_SEPARATOR.".."));
    }

    define('BASEPATH', __DIR__);
    define("CLASSES",BASEPATH.DIRECTORY_SEPARATOR."classes");
?>
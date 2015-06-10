<?php

require_once("settings/mysql.php");
require_once("settings/timezone.php");

function includeDir($directory)
{
    $files = scandir($directory);
    foreach ($files as $file) {
        if($file[0] != ".") {
            if(is_dir($file)) {
                includeDir($directory . "/" . $file);
            } else if (substr($file, -4) == ".php") {
                include_once($directory . "/" . $file); 
            }
        }
    }
}

includeDir("common");

global $mysqlDB;

$mysqlDB = new mysqlDB($host, $user, $password, $database);
$mysqlLoader = new mysqlLoader();

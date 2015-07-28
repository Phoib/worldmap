<?php

// Require the settings
require_once("settings/mysql.php");
require_once("settings/timezone.php");

/**
 * Scan a directory and include all listed PHP files
 *
 * @param string $directory The directory to scan for
 */
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

// Include all common functionalities
includeDir("common");
// Include all MVC structures
includeDir("objects");

// Declare a global mysqlDB
global $mysqlDB;

// Create a mysql, mysqlLoader
$mysqlDB = new mysqlDB($host, $user, $password, $database);
$mysqlLoader = new mysqlLoader();

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
            if(is_dir($directory . "/" . $file)) {
                includeDir($directory . "/" . $file);
            } else if (substr($file, -4) == ".php") {
                include_once($directory . "/" . $file); 
            }
        }
    }
}

/**
 * Scan the get parameters and put them in $_GET
 */
function parseGetParams()
{
    if(!isset($_GET['params'])) {
        $_GET = array();
        return;
    }

    $params = str_replace("index.php", "", $_GET['params']);
    $params = array_values(
        array_filter(
            explode("/", $params)
        )
    );
    $_GET = array();
    //If the count of array values is odd, we assume the first key defines the
    //game that is chosen. This can be overwritten later by a new key.
    if((count($params)%2) == 1) {
        $_GET['game'] = array_shift($params);
    }
    for($i=0;$i<count($params);$i+=2) {
        $key = $params[$i];
        $value = $params[$i+1];
        $_GET[$key] = $value;
    }
}

parseGetParams();

// Include all common functionalities
includeDir("common");
// Include all MVC structures
includeDir("mvc");
// Include all MVC structures
includeDir("objects");

// Declare a global mysqlDB
global $mysqlDB;

// Create a mysql, mysqlLoader
$mysqlDB = new mysqlDB($host, $user, $password, $database);
$mysqlLoader = new mysqlLoader();

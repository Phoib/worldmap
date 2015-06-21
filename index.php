<?php


require_once("include.php");

$phpUnit = new phpUnit("tests/source");

$phpUnit->executeTests();
$tests = $phpUnit->returnTests();
var_dump($tests);

$sqlLog = $mysqlDB->getSQLLog();

var_dump($sqlLog);

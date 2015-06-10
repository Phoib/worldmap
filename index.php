<?php


require_once("include.php");

$phpUnit = new phpUnit("tests/source");

$phpUnit->executeTests();

$sqlLog = $mysqlDB->getSQLLog();

var_dump($sqlLog);

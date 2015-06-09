<?php


require_once("include.php");

$phpUnit = new phpUnit("tests/source");

$phpUnit->executeTests();

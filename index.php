<?php

session_start();

require_once("include.php");

$users = new users();

$user = $users->verifySessionOrLogin();

switch($user['userId']) {
case users::NO_USER_NO_LOGIN:
    $users->printLoginScreen();
    exit(0);
    break;
case users::NO_USER_INCORRECT_LOGIN:
    $users->printLoginScreen("Wrong login details supplied!");
    exit(0);
    break;
}

$phpUnit = new phpUnit("tests/source");

$phpUnit->executeTests();
$testHtml = $phpUnit->returnTable();

$html = new html();
$html->head->setTitle("Worldmap tests");
$html->addHtml($testHtml);

$sqlLog = $mysqlDB->getSQLLog();
$sqlTable = array(
    array(
        "#", "Time", "Memory", "Query"
    )
);
foreach($sqlLog as $i => $log) {
    $log = explode(";", $log);
    $sqlTable[] = array(
        $i+1,
        str_replace("SQL Query: ", "", $log[0]),
        $log[1],
        str_replace(
            array(" query: [", "]"), 
            "", 
            $log[2]
        )
    );
}
$sqlTable = htmlChunk::generateTableFromArray($sqlTable, true);
$html->addHtml($sqlTable);


$html->render();
echo $html->getHtml();

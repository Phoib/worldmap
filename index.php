<?php


require_once("include.php");

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

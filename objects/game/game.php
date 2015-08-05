<?php

/**
 * This class describes the game Model object, used to control game objects
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class game extends model
{
    /**
     * @var array $game
     */
    protected $game = null;

    /**
     * @var int $id
     */
    protected $id;

    /**
     * Construct a users object
     */
    public function __construct()
    {
        $this->controller = new gameController("game");
        $this->view       = new gameView();

        $this->game = $this->controller->determineGame();
        $this->id = $this->game['id'];
        switch($this->id) {
            case -1:
                $games = $this->controller->getAllGames();
                //Move to proper view
                foreach($games as $game) {
                    if($game['id'] != $this->id) {
                        printf("<a href='index.php/%s'>%s</a><br>", $game['key'], $game['name']);
                    }
                }
                break;
            case -2:
                echo $this->game['name'];
                break;
            case -3:
                $this->handleDevGame();
                break;
            default:
                var_dump($this->game);
                break;
        }        
    }

    private function handleDevGame()
    {
        $phpUnit = new phpUnit("tests/source");

        $phpUnit->executeTests();
        $testHtml = $phpUnit->returnTable();

        //Move to view
        $html = new html();
        $html->head->setTitle("Worldmap tests");
        $html->addHtml($testHtml);

        global $mysqlDB;
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
    }
}

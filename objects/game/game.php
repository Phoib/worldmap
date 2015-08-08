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
     * Construct a game object, and handle it's printing
     */
    public function __construct()
    {
        $this->controller = new gameController("game");
        $this->view       = new gameView();

        $this->game = $this->controller->determineGame();
        $this->id = $this->game['id'];

        switch($this->id) {
            case -1:
                $this->handleLinks();
                break;
            case -2:
                $this->handleAdminGame();
                break;
            case -3:
                $this->handleDevGame();
                break;
            default:
                $this->handleGame();
                break;
        }        
        $sqlTable = $this->generateSQLDebugTable();
        $this->view->addHtml($sqlTable);

        $this->view->render();
        echo $this->view->getHtml();
    }

    /**
     * Make sure the selected game is handled properly
     */
    private function handleGame()
    {
        $games = $this->controller->getAllGames();
        $this->view->generateLinkScreen($games, $this->id);
        $this->view->generateGameScreen($this->game);
    }

    /**
     * Make sure all links are handled properly
     */
    private function handleLinks()
    {
        $games = $this->controller->getAllGames();
        $this->view->generateLinkScreen($games, $this->id);
    }

    /**
     * Handle all admin functionalities
     */
    private function handleAdminGame()
    {
        $games = $this->controller->getAllGames();
        $this->view->generateLinkScreen($games, $this->id);
        $this->view->generateAdminScreen();
    }

    /**
     * Make sure all development functions are handled
     */
    private function handleDevGame()
    {
        $games = $this->controller->getAllGames();
        $this->view->generateLinkScreen($games, $this->id);

        $phpUnit = new phpUnit("tests/source");

        $phpUnit->executeTests();
        $testHtml = $phpUnit->returnTable();

        $this->view->generateDevelScreen($testHtml);
    }

    /**
     * Generates the SQL Debug table. Moved here for convenience,
     * should be properly placed later.
     */
    private function generateSQLDebugTable()
    {
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
        return htmlChunk::generateTableFromArray($sqlTable, true);
    }
}

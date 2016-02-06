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
    const CHANGE_MENU = 2;
    const KEY_EXISTS  = -1;

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
        $this->view->setGameKey($this->game['key']);

        $games = $this->controller->getAllGames();
        $menu = new menu();
        $menuHtml = $menu->returnMenu($this->id, $this->game['key'], $games);
        $this->view->addHtml($menuHtml['menu'], 'menu');
        $this->view->setJavascript($menuHtml['javascript']);

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
    }

    /**
     * Return the HTML generated by the view
     *
     * @return string   The HTML
     */
    public function returnHtml()
    {
        return $this->view->getHtml();
    }

    /**
     * Make sure the selected game is handled properly
     */
    protected function handleGame()
    {
        $this->view->generateGameScreen($this->game);
    }

    /**
     * Make sure all links are handled properly
     */
    protected function handleLinks()
    {
    }

    /**
     * Handle all admin functionalities
     */
    protected function handleAdminGame()
    {
        if(!empty($_POST)) {
            $return = $this->controller->handleAdminPost();
            switch($return) {
            case self::CHANGE_MENU:
                $games = $this->controller->getAllGames();
                $menu = new menu();
                $menuHtml = $menu->returnMenu($this->id, $this->game['key'], $games);
                $this->view->addHtml($menuHtml['menu'], 'menu');
                break;
            case static::KEY_EXISTS:
                $game = array(
                    'name' => $_POST['name'],
                    'warning' => static::KEY_EXISTS
                );
                $_GET['id'] = 'new';
                $this->view->editGame($game);
                return;
                break;
            }
        }
        if(!isset($_GET['menu'])) {
            $_GET['menu'] = "";
        }   
        switch($_GET['menu']) {
        case 'game':
            if(isset($_GET['id'])) {
                $game = $this->controller->getGame($_GET['id']);
                $this->view->editGame($game);
            } else{
                $games = $this->controller->getAllGames();
                $this->view->generateGameEditScreen($games);
            }
        break;
        default:
            $this->view->generateAdminScreen();
        break;
        }
    }

    /**
     * Make sure all development functions are handled
     */
    protected function handleDevGame()
    {
        $phpUnit = new phpUnit("tests/source");

        $phpUnit->executeTests();
        $testHtml = $phpUnit->returnTable();

        $this->view->generateDevelScreen($testHtml);
    }

    /**
     * Generates the SQL Debug table. Moved here for convenience,
     * should be properly placed later.
     */
    protected function generateSQLDebugTable()
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
        return htmlChunk::generateTableFromArray($sqlTable, true, true);
    }
}

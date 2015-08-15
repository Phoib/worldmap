<?php

/**
 * This class describes the gameController Test object, used to test the gameController class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class gameControllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var array Properties which should not be serialized by phpUnit
     */
    protected $backupGlobalsBlacklist = array('mysqlDB');

    /**
     * @var \MysqlDB        MySQL object
     */
    private $mysql;

    /**
     * @var \MysqlLoader    MySQL Loader object
     */
    private $mysqlLoader;

    /**
     * @var \gameController GameController object
     */
    private $controller;

    /**
     * Setup the tests
     */
    public function setUp()
    {
        $_SESSION = array();
    	global $mysqlDB;
        global $testdatabase;	
    	$mysqlDB->changeDB($testdatabase);
        $this->mysqlLoader = new mysqlLoader();
        $this->mysqlLoader->loadSQLFile("tests/sql/objects/game/game.sql");
        $this->controller = new gameController("game");
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        unset($_SESSION);
        $this->mysqlLoader->dropTables();
        $this->mysqlObject = null;
        $this->controller = null;
    }

    /**
     * Tests the determineGame functionality
     */
    public function test_determineGame()
    {
        $expected = array(
            "id" => -1,
            "name" => "Selection",
            "key" => ""
        );
        $actual = $this->controller->determineGame();
        $this->assertEquals($expected, $actual, "The game with id -1 is not loaded");

        $_GET['game'] = "NotThereMate";
        $actual = $this->controller->determineGame();
        $this->assertEquals($expected, $actual, "The non existent game did not default to id -1");

        $_GET['game'] = "injection' OR 1;";
        $actual = $this->controller->determineGame();
        $this->assertEquals($expected, $actual, "The SQL injection did not default to id -1");

        $expected = array(
            "id" => -2,
            "name" => "Admin",
            "key" => "admin"
        );
        $_GET['game'] = "admin";
        $actual = $this->controller->determineGame();
        $this->assertEquals($expected, $actual, "The game with id -2 did not get loaded");
 
        $expected = array(
            "id" => -3,
            "name" => "Development",
            "key" => "devel"
        );
        $_GET['game'] = "devel";
        $actual = $this->controller->determineGame();
        $this->assertEquals($expected, $actual, "The game with id -3 did not get loaded");

        $expected = array(
            "id" => 1,
            "name" => "Test Worldmap",
            "key" => "test"
        );
        $_GET['game'] = "test";
        $actual = $this->controller->determineGame();
        $this->assertEquals($expected, $actual, "The game with id 1 did not get loaded");
    }

    /**
     * Tests to get all the games
     */
    public function test_getAllGames()
    {
        $expected = array(
            array(
                "id" => -3,
                "name" => "Development",
                "key" => "devel"
            ),    
            array(
                "id" => -2,
                "name" => "Admin",
                "key" => "admin"
            ),    
            array(
                "id" => -1,
                "name" => "Selection",
                "key" => ""
            ),    
            array(
                "id" => 1,
                "name" => "Test Worldmap",
                "key" => "test"
            ),    
        );
        $actual = $this->controller->getAllGames();
        $this->assertEquals($expected, $actual, "Not all games were loaded!");
    }
}

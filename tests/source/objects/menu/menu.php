<?php

/**
 * This class describes the menu Test object, used to test the menu class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class menuTest extends PHPUnit_Framework_TestCase
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
     * @var \menu           Game object
     */
    private $menu;

    /**
     * Setup the tests
     */
    public function setUp()
    {
        $_SESSION = array();
        $_POST = array();
    	global $mysqlDB;
        global $testdatabase;	
    	$mysqlDB->changeDB($testdatabase);
        $this->mysqlLoader = new mysqlLoader();
        $this->mysqlLoader->loadSQLFile("tests/sql/objects/menu/menu.sql");

        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['SERVER_NAME'] = "localhost";
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        unset($_SESSION);
        unset($_POST);
        $this->mysqlLoader->dropTables();
        $this->mysqlObject = null;
    }

    /**
     * Get the normal menu screen
     */
    public function testMenu()
    {
        $game = -1;
        $gameName = "selection";
        $menu = new menu();
        $menuParts = $menu->returnMenu($game, $gameName, array());
        $actual = array_keys($menuParts);
        $expected = array("javascript", "menu");
        $this->assertEquals($expected, $actual, "Logout was not rendered properly!");
    }
}

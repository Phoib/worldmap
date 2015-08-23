<?php

/**
 * This class describes the menuController Test object, used to test the menuController class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class menuControllerTest extends PHPUnit_Framework_TestCase
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
     * @var \menuController GameController object
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
        $this->mysqlLoader->loadSQLFile("tests/sql/objects/menu/menu.sql");
        $this->controller = new menuController("menu");
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

    public function testGetMenuForGame()
    {
        $game = 0;
        $expected = array(
            array(
                'id' => 1,
                'game' => 0,
                'key' => 'logout',
                'name' => 'Logout',
                'order' => 100
            )
        );
        $actual = $this->controller->getMenuForGame($game);
        $this->assertEquals($expected, $actual, "Logout isn't shown!");

        $game = 1;
        $expected = array(
            array(
                'id' => 1,
                'game' => 0,
                'key' => 'logout',
                'name' => 'Logout',
                'order' => 100
            )
        );
        $actual = $this->controller->getMenuForGame($game);
        $this->assertEquals($expected, $actual, "Base game has items!");

        $game = -1;
        $expected = array(
            array(
                'id' => 1,
                'game' => 0,
                'key' => 'logout',
                'name' => 'Logout',
                'order' => 100
            )
        );
        $actual = $this->controller->getMenuForGame($game);
        $this->assertEquals($expected, $actual, "Selection items aren't shown!");

        $game = -2;
        $expected = array(
            array(
                'id' => 4,
                'game' => -2,
                'key' => 'game',
                'name' => 'Game creator',
                'order' => 1
            ),
            array(
                'id' => 5,
                'game' => -2,
                'key' => 'user',
                'name' => 'Users editor',
                'order' => 2
            ),
            array(
                'id' => 1,
                'game' => 0,
                'key' => 'logout',
                'name' => 'Logout',
                'order' => 100
            )
        );
        $actual = $this->controller->getMenuForGame($game);
        $this->assertEquals($expected, $actual, "Admin items aren't shown!");

        $game = -3;
        $expected = array(
            array(
                'id' => 2,
                'game' => -3,
                'key' => 'test',
                'name' => 'Testsuite',
                'order' => 1
            ),
            array(
                'id' => 3,
                'game' => -3,
                'key' => 'install',
                'name' => 'Installer creator',
                'order' => 2
            ),

            array(
                'id' => 1,
                'game' => 0,
                'key' => 'logout',
                'name' => 'Logout',
                'order' => 100
            )
        );
        $actual = $this->controller->getMenuForGame($game);
        $this->assertEquals($expected, $actual, "Devel items aren't shown!");
    }
}

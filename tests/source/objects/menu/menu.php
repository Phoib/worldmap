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
    public function testOnlyLogout()
    {
        $game = -1;
        $gameName = "selection";
        $menu = new menu();
        $htmlChunk = $menu->returnMenu($game, $gameName);
        $actual = $htmlChunk->render();
        $expected = "<table>\n  <tr>\n    <td>\n      <a href='http://localhost/index.php/selection/menu/logout'>\n        Logout\n      </a>\n    </td>\n  </tr>\n</table>\n";
        $this->assertEquals($expected, $actual, "Logout was not rendered properly!");
    }

    /**
     * Get the admin menu screen
     */
    public function testAdminMenu()
    {
        $game = -2;
        $gameName = "admin";
        $menu = new menu();
        $htmlChunk = $menu->returnMenu($game, $gameName);
        $actual = $htmlChunk->render();
        $expected = "<table>\n  <tr>\n" .
            "    <td>\n      <a href='http://localhost/index.php/admin/menu/game'>\n        Game creator\n      </a>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/admin/menu/user'>\n        Users editor\n      </a>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/admin/menu/logout'>\n        Logout\n      </a>\n    </td>\n" .
            "  </tr>\n</table>\n";
        $this->assertEquals($expected, $actual, "Admin screen was not rendered properly!");
    }

    /**
     * Test the devel screen
     */
    public function testDevelMenu()
    {
        $game = -3;
        $gameName = "devel";
        $menu = new menu();
        $htmlChunk = $menu->returnMenu($game, $gameName);
        $actual = $htmlChunk->render();
        $expected = "<table>\n  <tr>\n" .
            "    <td>\n      <a href='http://localhost/index.php/devel/menu/test'>\n        Testsuite\n      </a>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/devel/menu/install'>\n        Installer creator\n      </a>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/devel/menu/logout'>\n        Logout\n      </a>\n    </td>\n" .
            "  </tr>\n</table>\n";
        $this->assertEquals($expected, $actual, "Devel screen was not rendered properly!");
    }
}

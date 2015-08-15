<?php

/**
 * This class describes the game Test object, used to test the game class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class gameTest extends PHPUnit_Framework_TestCase
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
     * @var \game           Game object
     */
    private $game;

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
        $this->mysqlLoader->loadSQLFile("tests/sql/objects/game/game.sql");

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
     * Test the game with no id given.
     * Currently untestable.
     */
    public function test_noIdGame()
    {
        $this->markTestIncomplete(
                      'This test has not been implemented yet.'
        );
        $_GET['test'];
        $game = new game();
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title>Worldmap links</title>\n  </head>\n  <body>\n";
        $expected .= $this->returnLinksTable();
    
        $actual = $game->returnHtml();
        $this->assertEquals($expected, $actual, "Links not rendered!");
    }
    
    private function returnLinksTable()
    {
        return "    <table>\n      <tr>\n        <td>\n          <a href='http://localhost/common/index.php/devel'>\n            Development\n          </a>\n        </td>\n      </tr>\n      <tr>\n        <td>\n          <a href='http://localhost/common/index.php/admin'>\n            Admin\n          </a>\n        </td>\n      </tr>\n      <tr>\n        <td>\n          <a href='http://localhost/common/index.php/test'>\n            Test Worldmap\n          </a>\n        </td>\n      </tr>\n    </table>\n";
    }
}

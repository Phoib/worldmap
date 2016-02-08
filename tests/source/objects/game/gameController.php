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
            "key" => "",
            "permission" => 3
        );
        $user = array('permission' => 3);
        $actual = $this->controller->determineGame($user);
        $this->assertEquals($expected, $actual, "The game with id -1 is not loaded");

        $_GET['game'] = "NotThereMate";
        $actual = $this->controller->determineGame($user);
        $this->assertEquals($expected, $actual, "The non existent game did not default to id -1");

        $_GET['game'] = "injection' OR 1;";
        $actual = $this->controller->determineGame($user);
        $this->assertEquals($expected, $actual, "The SQL injection did not default to id -1");

        $expected = array(
            "id" => -2,
            "name" => "Admin",
            "key" => "admin",
            "permission" => 2
        );
        $_GET['game'] = "admin";
        $actual = $this->controller->determineGame($user);
        $this->assertFalse($actual, "The level is not sufficient enough for admin");
        $user = array('permission' => 2);
        $actual = $this->controller->determineGame($user);
        $this->assertEquals($expected, $actual, "The game with id -2 did not get loaded");
 
        $expected = array(
            "id" => -3,
            "name" => "Development",
            "key" => "devel",
            "permission" => 1
        );
        $_GET['game'] = "devel";
        $actual = $this->controller->determineGame($user);
        $this->assertFalse($actual, "The level is not sufficient enough for dev");
        $user = array('permission' => 1);
        $actual = $this->controller->determineGame($user);
        $this->assertEquals($expected, $actual, "The game with id -3 did not get loaded");

        $expected = array(
            "id" => 1,
            "name" => "Test Worldmap",
            "key" => "test",
            "permission" => 3
        );
        $_GET['game'] = "test";
        $actual = $this->controller->determineGame($user);
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
                "key" => "devel",
                "permission" => 1
            ),    
            array(
                "id" => -2,
                "name" => "Admin",
                "key" => "admin",
                "permission" => 2
            ),    
            array(
                "id" => -1,
                "name" => "Selection",
                "key" => "",
                "permission" => 3
            ),    
            array(
                "id" => 1,
                "name" => "Test Worldmap",
                "key" => "test",
                "permission" => 3
            ),    
        );
        $actual = $this->controller->getAllGames();
        $this->assertEquals($expected, $actual, "Not all games were loaded!");
    }

    /**
     * Tests to get specific game
     */
    public function test_getGame()
    {
        $expected = array(
            "id" => 1,
            "name" => "Test Worldmap",
            "key" => "test",
            "permission" => 3
        );
        $actual = $this->controller->getGame(1);
        $this->assertEquals($expected, $actual, "The correct game was not loaded!");
    }

    /**
     * Test admin functionality
     */
    public function testHandleAdminPostGame()
    {
        $this->assertNull($this->controller->handleAdminPost(), "Without an action, nothing should happen");
        $_POST['action'] = 'newGame';
        $_POST['key'] = 'test';
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::KEY_EXISTS,
            "Should throw a warning if the key exists!"
        );
        $expected = array(
            "id" => 2,
            "name" => "new",
            "key" => "newKey",
            "permission" => 3
        );
        $_POST['key'] = $expected['key'];
        $_POST['name'] = $expected['name'];
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::CHANGE_MENU,
            "Should say to reload the menu!"
        );
        $actual = $this->controller->getGame(2);
        $this->assertEquals($expected, $actual, "The correct game was not loaded!");
        $_POST['action'] = 'editGame';
        $_POST['id'] = 1;
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::KEY_EXISTS,
            "Should throw a warning if the key exists!"
        );
        $expected['name'] = "newer";
        $expected['key'] = 'newerKey';
        $_POST['id'] = $expected['id'];
        $_POST['key'] = $expected['key'];
        $_POST['name'] = $expected['name'];
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::CHANGE_MENU,
            "Should say to reload the menu!"
        );
        $actual = $this->controller->getGame(2);
        $this->assertEquals($expected, $actual, "The correct game was not loaded!");
    }

    /**
     * Test admin functionality for users
     */
    public function testHandleAdminPostUsers()
    {
        $this->mysqlLoader->loadSQLFile("tests/sql/objects/users/users.sql");
        $this->assertNull($this->controller->handleAdminPost(), "Without an action, nothing should happen");
        $_POST['action'] = 'newUser';
        $_POST['username'] = 'admin';
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::KEY_EXISTS,
            "Should throw a warning if the key exists!"
        );
        $_POST['username'] = 'ADmin';
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::KEY_EXISTS,
            "Should throw a warning if the key exists, no matter the casing of the characters"
        );
        $expected = array(
            "id" => 2,
            "username" => "new",
            "password" => "123",
            "permission" => 3
        );
        $_POST['username'] = $expected['username'];
        $this->assertEquals(
            game::EMPTY_PASSWORD,
            $this->controller->handleAdminPost(),
            "Should return error code if no password is defined"
        );

        $_POST['password'] = $expected['password'];
        $this->assertEquals(
            2,
            $this->controller->handleAdminPost(),
            "Should return the new ID for new users"
        );
        $actual = $this->controller->getUser(2);
        $userController = new UsersController("users");
        $expected["password"] = $userController->hashPlainTextToPassword($_POST['password'], $actual['salt']);
        $expected["salt"] = $actual['salt'];
        
        $this->assertEquals($expected, $actual, "The correct user was not loaded!");

        $_POST['action'] = 'editUser';
        $_POST['id'] = 1;
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::KEY_EXISTS,
            "Should throw a warning if the username exists!"
        );
        $expected['username'] = "newer";
        $expected['password'] = 'newerKey';
        $_POST['id'] = $expected['id'];
        $_POST['password'] = $expected['password'];
        $_POST['username'] = $expected['username'];
        $this->assertEquals(
            $this->controller->handleAdminPost(),
            game::SUCCESS,
            "Should say to reload the menu!"
        );
        $actual = $this->controller->getUser(2);
        $expected["password"] = $userController->hashPlainTextToPassword($_POST['password'], $actual['salt']);
        $expected["salt"] = $actual['salt'];
        $this->assertEquals($expected, $actual, "The correct game was not loaded!");

    }

}

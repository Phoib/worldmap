<?php

/**
 * This class describes the usersController Test object, used to test the usersController class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class usersControllerTest extends PHPUnit_Framework_TestCase
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
     * @var \users          Users object
     */
    private $usersController;

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
        $this->mysqlLoader->loadSQLFile("tests/sql/objects/users/users.sql");
        $this->controller = new usersController("users");
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
     * Test the user session checking
     */
    public function test_checkUserSession()
    {
        $_SESSION['userId'] = -1;
        $_SESSION['userSecret'] = "";
        $expected = array("userId" => users::NO_USER_NO_LOGIN);
        $actual = $this->controller->checkUserSession();
        $this->assertEquals($expected, $actual, "Incorrect mysql user was logged in!");

        $_SESSION['userId'] = 1;
        $actual = $this->controller->checkUserSession();
        $this->assertEquals($expected, $actual, "Incorrect userSecret was logged in!");

        $_SESSION['userSecret'] = "5b36224bb3d8c8bb771136fa7a92879b4e5de18d35c7418f2c52cd0ee2c9d16f8eedda7f501855ecf1808ce44ce33902e4e322ba08278d144fea41346855a56e";
        $expected = array(
            "userId" => 1,
            "username" => "admin"
        );
        $actual = $this->controller->checkUserSession();
        $this->assertEquals($expected, $actual, "User should have a session now!");
    }
    
    /**
     * Test the login verification
     */
    public function test_verifyLogin()
    {
        $username = 'foo';
        $password = 'bar';
        $expected = array("userId" => users::NO_USER_INCORRECT_LOGIN);
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "False combination should not login!");

        $username = 'admin';
        $password = 'bar';
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "False password should not login!");

        $password = '123test';
        $expected = array(
            "userId" => 1,
            "username" => "admin"
        );
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "User should be logged in now!");
    }

    /**
     * Check user login with SQL injection
     */
    public function test_verifyLoginWithSQLInjection()
    {
        $username = "bla' OR 1";
        $password = '123test';
        $expected = array("userId" => users::NO_USER_INCORRECT_LOGIN);
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "Simple SQL injection with ' should not login!");

        $username = 'bla" OR 1';
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "Simple SQL injection with \" should not login!");

        $username = "bla' OR id = 1";
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "Simple SQL injection with id and ' should not login!");

        $username = 'bla" OR id = 1';
        $actual = $this->controller->verifyLogin($username, $password);
        $this->assertEquals($expected, $actual, "Simple SQL injection with id and \" should not login!");
    }

    /**
     * Test the password hashing
     */
    public function test_hashPlainTextToPassword()
    {
        $salt = "861ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $password = "123test";
        $expected = "bd6ef2f4d5f80b2a246953030e683db347e6a208207917962adb36d332ce2f081809e1a5202e07c8260c85d2e3dfa19ba80cdaaec92d5413c995bb83b030c544";
        $actual = $this->controller->hashPlainTextToPassword($password, $salt);
        $this->assertEquals($expected, $actual, "Supplied password and salt do not match? They should!");

        $salt = "961ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $actual = $this->controller->hashPlainTextToPassword($password, $salt);
        $this->assertNotEquals($expected, $actual, "Wrong salt gets hashed to the same hash! Hash collision!");

        $salt = "861ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $password = "223test";
        $actual = $this->controller->hashPlainTextToPassword($password, $salt);
        $this->assertNotEquals($expected, $actual, "Wrong password gets hashed to the same hash! Hash collision!");

        $salt = "961ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $actual = $this->controller->hashPlainTextToPassword($password, $salt);
        $this->assertNotEquals($expected, $actual, "Wrong salt and password gets hashed to the same hash! Hash collision!");
    }

    /**
     * Test the session secret hashing
     */
    public function test_hashSessionSecret()
    {
        $expected = "5b36224bb3d8c8bb771136fa7a92879b4e5de18d35c7418f2c52cd0ee2c9d16f8eedda7f501855ecf1808ce44ce33902e4e322ba08278d144fea41346855a56e";

        $id = 1;
        $salt = "861ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $username = "admin";

        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertEquals($expected, $actual, "Supplied id, salt, username do not match the secret!");

        $id = 2;
        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertNotEquals($expected, $actual, "Wrong id makes a matching secret!");

        $salt = "961ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertNotEquals($expected, $actual, "Wrong id, wrong salt makes a matching secret!");

        $salt = "861ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $username = "Admin";
        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertNotEquals($expected, $actual, "Wrong id, wrong username makes a matching secret!");

        $id = 1;
        $salt = "961ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertNotEquals($expected, $actual, "Wrong salt makes a matching secret!");

        $username = "Admin";
        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertNotEquals($expected, $actual, "Wrong salt, wrong username makes a matching secret!");

        $salt = "861ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $actual = $this->controller->hashSessionSecret($id, $salt, $username);
        $this->assertNotEquals($expected, $actual, "Wrong username makes a matching secret!");

        $id = 2;
        $salt = "961ac332ee0f63ea5cf905c41593b063f48caa3a93bdf9fb54cfc5432e659df1";
        $this->assertNotEquals($expected, $actual, "Wrong id, wrong salt, wrong username makes a matching secret!");
    }

    /**
     * Test the salt generation. Two identical salts should not be generated
     */
    public function test_generateSalt()
    {
        $salt1 = $this->controller->generateSalt();
        $salt2 = $this->controller->generateSalt();
        $this->assertNotEquals($salt1, $salt2, "Two salts should not be the same!");
    }
}

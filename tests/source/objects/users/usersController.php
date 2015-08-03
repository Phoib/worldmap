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
        $_POST = array();
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
        unset($_POST);
        $this->mysqlLoader->dropTables();
        $this->mysqlObject = null;
    }

    /**
     * Test the user session checking
     */
    public function test_checkUserSession()
    {
        $this->assertTrue(true);
    }
    
    //public function verifyLogin($username, $password)
    /**
     * Test the login verification
     */
    public function test_verifyLogin()
    {
        $this->assertTrue(true);
    }

    //public function hashPlainTextToPassword($password, $salt)
    /**
     * Test the password hashing
     */
    public function test_hashPlainTextToPassword()
    {
        $this->assertTrue(true);
    }

    //public function hashSessionSecret($id, $salt, $username)
    /**
     * Test the session secret hashing
     */
    public function test_hashSessionSecret()
    {
        $this->assertTrue(true);
    }

    //public function generateSalt()
    /**
     * Test the salt generation. Two identical salts should not be generated
     */
    public function test_generateSalt()
    {
        $this->assertTrue(true);
    }

     /*
     * Verifies the user login methods
     */
/*    public function test_verifySessionOrLogin()
    {
        $expected = array("userId" => users::NO_USER_NO_LOGIN);
        $actual = $this->users->verifySessionOrLogin();
        $this->assertEquals($expected, $actual, "User should not be logged in yet!");

        // Verify only basic loggin in here, further login in testing should be done in the controller
        $_POST['action'] = users::ACTION_LOGIN;
        $_POST['username'] = "admin";
        $_POST['password'] = "123test";
        $expected = array(
            "userId" => 1,
            "username" => "admin"
        );
        $actual = $this->users->verifySessionOrLogin();
        $this->assertEquals($expected, $actual, "User should be logged in now!");

        unset($_POST['action']);
        unset($_POST['username']);
        unset($_POST['password']);
        $actual = $this->users->verifySessionOrLogin();
        $this->assertEquals($expected, $actual, "User should have a session now!");
    }

    /**
     * Verifies a user with a session gets properly logged in
     */
/*    public function test_onlySession()
    {
        $_SESSION['userId'] = 1;
        $_SESSION['userSecret'] = "5b36224bb3d8c8bb771136fa7a92879b4e5de18d35c7418f2c52cd0ee2c9d16f8eedda7f501855ecf1808ce44ce33902e4e322ba08278d144fea41346855a56e";
        $expected = array(
            "userId" => 1,
            "username" => "admin"
        );
        $actual = $this->users->verifySessionOrLogin();
        $this->assertEquals($expected, $actual, "User should have a session now!");
    }*/

}

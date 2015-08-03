<?php

/**
 * This class describes the usersView Test object, used to test the usersView class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class usersViewTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \usersView  UsersView object
     */
    private $view;

    /**
     * Setup the tests
     */
    public function setUp()
    {
        $this->view = new usersView();
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        $this->view = null;
    }


    /**
     * Verifies the user login screen
     */
    public function test_printLoginScreen()
    {
        $expected = trim(file_get_contents("tests/testfiles/objects/users/login.html"));
        $message = "";
        $actual = $this->view->printLoginScreen($message);
        $this->assertEquals($expected, $actual, "Login screen is not correct!");

        $this->view = new usersView();
        $expected = trim(file_get_contents("tests/testfiles/objects/users/loginMessage.html"));
        $message = "Here be a message";
        $actual = $this->view->printLoginScreen($message);
        $this->assertEquals($expected, $actual, "Login screen with message is not correct!");
    }
}

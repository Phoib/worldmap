<?php

/**
 * This class describes the menuView Test object, used to test the menuView class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class menuViewTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \menuView  menuView object
     */
    private $view;

    /**
     * Setup the tests
     */
    public function setUp()
    {
        $this->view = new menuView();

        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['SERVER_NAME'] = "localhost";
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        $this->view = null;
    }

    /**
     * Show only the logout
     */
    public function testLogout()
    {
        $gameName = "test";
        $menuItems = array(
            array(
                'key' => 'logout',
                'name' => "Logout"
            )
        );
        $htmlArray = $this->view->createHtmlMenu($menuItems, $gameName, 0, array());
        $htmlChunk = $htmlArray['menu'];
        $actual = $htmlChunk->render();
        $expected = "<table>\n  <tr>\n" .
            "    <td>\n      <select name='gameSelect' id='gameSelect' onchange='selectGame(this)'>\n" .
	    "        <option>\n          Select a game\n        </option>\n      </select>\n    </td>\n" .
	    "    <td>\n      <a href='http://localhost/index.php/test/menu/logout'>\n        Logout\n      </a>\n    </td>\n" .
	    "  </tr>\n</table>\n";
        $this->assertEquals($expected, $actual, "Logout was not rendered properly!");
    }

    public function testAdminScreen()
    {
        $gameName = "admin";
        $menuItems = array(
            array(
                'key' => "game",
                'name' => "Game creator"
            ),
            array(
                'key' => "user",
                'name' => "User editor"
             ),
             array(
                'key' => 'logout',
                'name' => "Logout"
            )
        );
        $htmlArray = $this->view->createHtmlMenu($menuItems, $gameName, 0, array());
        $htmlChunk = $htmlArray['menu'];
        $actual = $htmlChunk->render();
        $expected = "<table>\n  <tr>\n" .
            "    <td>\n      <select name='gameSelect' id='gameSelect' onchange='selectGame(this)'>\n" .
	    "        <option>\n          Select a game\n        </option>\n      </select>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/admin/menu/game'>\n        Game creator\n      </a>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/admin/menu/user'>\n        User editor\n      </a>\n    </td>\n" .
            "    <td>\n      <a href='http://localhost/index.php/admin/menu/logout'>\n        Logout\n      </a>\n    </td>\n" .
            "  </tr>\n</table>\n";
        $this->assertEquals($expected, $actual, "Admin screen was not rendered properly!");
    }
}

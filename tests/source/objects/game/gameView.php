<?php

/**
 * This class describes the gameView Test object, used to test the gameView class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage objects
 */
class gameViewTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \gameView  gameView object
     */
    private $view;

    /**
     * Setup the tests
     */
    public function setUp()
    {
        $this->view = new gameView();
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        $this->view = null;
    }

    /**
     * Test generate Devel Screen
     */
    public function test_generateDevelScreen()
    {
        $testHTML = "test";
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title>Worldmap tests</title>\n  </head>\n  <body>\n    test\n  </body>\n</html>";
        $this->view->generateDevelScreen($testHTML);
        $this->view->render();
        $actual = $this->view->getHtml();
        $this->assertEquals($expected, $actual, "The development screen was not rendered properly");
    }

    /**
     * Test generate Admin Screen
     */
    public function test_generateAdminScreen()
    {
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title>Worldmap admin</title>\n  </head>\n  <body>\n" .
          "    <table>\n" .
          "      <tr>\n        <td>\n          Amount of games\n        </td>\n        <td>\n          3\n        </td>\n      </tr>\n" .
          "      <tr>\n        <td>\n          Amount of users\n        </td>\n        <td>\n          2\n        </td>\n      </tr>\n" .
          "    </table>\n" .
          "  </body>\n</html>";
        $this->view->generateAdminScreen(array('users' => 2, 'game' => 3));
        $this->view->render();
        $actual = $this->view->getHtml();
        $this->assertEquals($expected, $actual, "The admin screen was not rendered properly");
    }

    /**
     * Test generate game Screen
     */
    public function test_generateGameScreen()
    {
        $game = array(
            "id" => 1,
            "name" => "Test",
            "key" => "test"
        );
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title>Test</title>\n  </head>\n  <body>\n    <table>\n      <tr>\n        <td>\n          id\n        </td>\n        <td>\n          1\n        </td>\n      </tr>\n      <tr>\n        <td>\n          name\n        </td>\n        <td>\n          Test\n        </td>\n      </tr>\n      <tr>\n        <td>\n          key\n        </td>\n        <td>\n          test\n        </td>\n      </tr>\n    </table>\n  </body>\n</html>";
        $this->view->generateGameScreen($game);
        $this->view->render();
        $actual = $this->view->getHtml();
        $this->assertEquals($expected, $actual, "The game screen was not rendered properly");
    }

    /**
     * Test generate link Screen
     */
    public function test_generateLinkScreen()
    {
        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['SERVER_NAME'] = "localhost";

        $games = array(
            array(
                "id" => 1,
                "name" => "Test",
                "key" => "test"
            ),
            array(
                "id" => 2,
                "name" => "Do not show me",
                "key" => "nope"
            ),
        );
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title>Worldmap links</title>\n    <script type='text/javascript'>\n\n            function selectGame(obj) {\n                var urlString = 'http://localhost/index.php/';\n                var selectedGame = obj.options[obj.selectedIndex];\n                if (selectedGame != '') {\n                    window.location = urlString + selectedGame.value;\n                }\n            }\n    </script>\n  </head>\n  <body>\n    <select name='gameSelect' id='gameSelect' onchange='selectGame(this)'>\n      <option>\n        Select a game\n      </option>\n      <option value='test'>\n        Test\n      </option>\n    </select>\n  </body>\n</html>";
        $this->view->generateLinkScreen($games, 2);
        $this->view->render();
        $actual = $this->view->getHtml();
        $this->assertEquals($expected, $actual, "The link screen was not rendered properly");
    }


}

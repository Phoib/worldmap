<?php

/**
 * This class describes the view Test object, used to test the view class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage mvc
 */
class viewTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test the title set functionality
     */
    public function test_titleTest()
    {
        $view = new view();
        $view->setTitle('test');
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title>test</title>\n  </head>\n  <body>\n  </body>\n</html>";
        $view->render();
        $actual = $view->getHtml();
        $this->assertEquals($expected, $actual, "The title was not set!");
    }

    /**
     * Test the javascript functions
     */
    public function test_javascript()
    {
        $view = new view();
        $view->setJavascript("alert('test')");
        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title></title>\n    <script type='text/javascript'>\nalert('test')\n    </script>\n  </head>\n  <body>\n  </body>\n</html>";
        $view->render();
        $actual = $view->getHtml();
        $this->assertEquals($expected, $actual, "The javascript was not set!");

    }
}

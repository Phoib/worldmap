<?php

/**
 * This class describes the htmlHead Test object, used to test the htmlHead class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class htmlHeadTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests the set and get functions
     */
    public function testSetGet()
    {
        $htmlHead = new htmlHead();
        $actual = $htmlHead->getTitle();
        $this->assertEquals("", $actual, "HTML head does not have an empty title!");

        $title = "Worldmap";
        $htmlHead->setTitle($title);
        $actual = $htmlHead->getTitle();
        $this->assertEquals($title, $actual, "Title was not set!");

        $javascript = "alert('test')";
        $htmlHead->setJavascript($javascript);
        $actual = $htmlHead->getJavascript();
        $this->assertEquals($javascript, $actual, "Javascript was not set!");
    }

    /**
     * Tests the render functionality
     */
    public function testRender()
    {
        $htmlHead = new htmlHead();
        $initial = $htmlHead->render();
        $expected = "  <head>\n    <title></title>\n  </head>\n";
        $this->assertEquals($expected, $initial, "HTML head was not initiated properly!");

        $title = "Worldmap";
        $htmlHead->setTitle($title);
        $expected = "  <head>\n    <title>$title</title>\n  </head>\n";
        $actual = $htmlHead->render();
        $this->assertEquals($expected, $actual, "HTML head does not have an empty title!");

        $javascript = "alert('test')";
        $htmlHead->setJavascript($javascript);
        $expected = "  <head>\n    <title>$title</title>\n    <script type='text/javascript'>\n$javascript\n    </script>\n  </head>\n";
        $actual = $htmlHead->render();
        $this->assertEquals($expected, $actual, "HTML head does not have javascript!");
    }

}

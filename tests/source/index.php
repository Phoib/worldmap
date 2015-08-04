<?php

/**
 * This class describes the phpUnit Test object, used to test the phpUnit class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage include
 */
class indexTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests if GET params are correctly parsed
     */
    public function test_parseGetParams()
    {
        $_GET = array();
        $expected = array();

        parseGetParams();
        $this->assertEquals($expected, $_GET, "Empty array is not empty");

        $_GET['bla'] = "blaat";
        parseGetParams();
        $this->assertEquals($expected, $_GET, "Params key not present, GET didn't turn empty");

        $_GET['params'] = "index.php/";
        parseGetParams();
        $this->assertEquals($expected, $_GET, "Empty index didn't stay empty");

        $_GET['params'] = "index.php/worldmap";
        $expected['game'] = "worldmap";
        parseGetParams();
        $this->assertEquals($expected, $_GET, "Only one argument didn't get turned into game");

        $_GET['params'] = "index.php/foo/bar";
        $expected = array('foo' => "bar");
        parseGetParams();
        $this->assertEquals($expected, $_GET, "Foo bar combination didn't get parsed");

        $_GET['params'] = "index.php/worldmap/foo/bar";
        $expected['game'] = "worldmap";
        parseGetParams();
        $this->assertEquals($expected, $_GET, "Worldmap, Foo bar combination didn't get parsed");
    }

}

<?php

/**
 * This class describes the controller Test object, used to test the controller class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage mvc
 */
class controllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test the title set functionality
     */
    public function test_collection()
    {
        $this->markTestIncomplete();
        $controller = new controller();

        $expected = array();
        $actual = $controller->getCollection();
        $this->assertEquals($expected, $actual, "Empty array was not empty!");

        
    }

}

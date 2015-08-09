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
}

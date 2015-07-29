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
     * @var \Controller     Controller object
     */
    private $controller;

    /**
     * Setup the tests
     */
    public function setUp()
    {
    	global $mysqlDB;
        global $testdatabase;	
    	$mysqlDB->changeDB($testdatabase);
        $this->mysqlLoader = new mysqlLoader();
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlObject.sql");
        $this->controller = new controller('dogs');
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        $this->mysqlLoader->dropTables();
        $this->mysqlObject = null;
    }

    /**
     * Test the controller inheriting mysqlObject
     */
    public function test_controller()
    {
        $mysqlObjectMethods = get_class_methods("mysqlObject");
        $controllerMethods = get_class_methods("controller");
        $missingObjectMethods = array_diff($mysqlObjectMethods, $controllerMethods);
        $expected = array();
        $this->assertEquals($expected, $missingObjectMethods, "Some methods are missing!");

        $expected = array(
            'id' => 0,
            'name' => '',
            'eyes' => 0,
            'birth' => '1970-01-01',
            'lastSeen' => '1970-01-01 01:00:01',
            'quote' => '',
            'weight' => 0.0,
        );

        $actual = $this->controller->returnInitialArray();
        $this->assertEquals($expected, $actual, "Description does not match!");
    }

    /**
     * Test the collection functionality, using an empty controller
     */
    public function test_collection()
    {
        $controller = new controller();

        $expected = array();
        $actual = $controller->getCollection();
        $this->assertEquals($expected, $actual, "Empty array was not empty!");

        $fakeObject = array(
            "foo" => "bar", 
            "true" => true,
            "false" => false,
            "int" => 13
        );
        $controller->addToCollection($fakeObject);
        $expected = array($fakeObject);
        $actual = $controller->getCollection();
        $this->assertEquals($expected, $actual, "Fake object not added!");

        $controller->addToCollection($fakeObject);
        $expected = array($fakeObject, $fakeObject);
        $actual = $controller->getCollection();
        $this->assertEquals($expected, $actual, "Second object not added!");

        $controller->resetCollection();
        $expected = array();
        $actual = $controller->getCollection();
        $this->assertEquals($expected, $actual, "Collection was not reset!");
    }

}

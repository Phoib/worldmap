<?php

/**
 * This class describes the MySQLObject Test object, used to test the MySQLObject class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class mysqlObjectTest extends PHPUnit_Framework_TestCase
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
     * Setup the tests
     */
    public function setUp()
    {
    	global $mysqlDB;
        global $testdatabase;	
    	$mysqlDB->changeDB($testdatabase);
        $this->mysqlLoader = new mysqlLoader();
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlObject.sql");
        $this->mysqlObject = new mysqlObject('dogs');
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
     * Tests the described object
     */
    public function testDescription()
    {
        $expected = array(
            'id' => 0,
            'name' => '',
            'eyes' => 0,
            'birth' => '1970-01-01',
            'lastSeen' => '1970-01-01 01:00:01',
            'quote' => '',
            'weight' => 0.0,
        );
        $actual = $this->mysqlObject->returnInitialArray();
        $this->assertEquals($expected, $actual, "Description does not match!");
    }

    /**
     * Tests the insert function
     */
    public function testInsert()
    {
        $values = array(
            'id' => 0,
            'name' => 'Fido',
            'eyes' => 2,
            'birth' => '2015-06-10',
            'lastSeen' => '2015-06-10 01:00:00',
            'quote' => 'Woef',
            'weight' => 10.0
        );
        $insertID = $this->mysqlObject->insert($values);
        $this->assertEquals(1, $insertID, "Dog got the wrong ID!");

        $expected = $values;
        $expected['id'] = 1;
        
        $actual = $this->mysqlObject->getById('dogs', 1);
        $this->assertEquals($expected, $actual, "The wrong dog was inserted");
    }

    /**
     * Tests an empty insert
     */
    public function testInitialInsert()
    {
    	$expected = $this->mysqlObject->returnInitialArray();
        $expected['id'] = 1;
        $id = $this->mysqlObject->insert($expected);
        $this->assertEquals(1, $id, "The inserted ID was not returned!");
    	$actual = $this->mysqlObject->getById('dogs', 1);
    	foreach($expected as &$value) {
            $value = (string)$value;
    	}
    	$this->assertEquals($expected, $actual, "Empty gets inserted correctly");
    }

    /**
     * Tests if all variables are correctly cleaned
     */
    public function testVariableCleaning()
    {
        $emptyDog = $this->mysqlObject->returnInitialArray();
    	$longName = $emptyDog;
    	$longName['name'] = "Fido The First of His Name, first Dog";
        $this->mysqlObject->insert($longName);
    	$longName['name'] = "Fido The First of His Name, fi";
        $actual = $this->mysqlObject->getById('dogs', 1);
    	$this->assertEquals($longName['name'], $actual['name'], "Name was not shortened");

    	$writtenEyes = $emptyDog;
    	$writtenEyes['eyes'] = "two";
    	$this->mysqlObject->insert($writtenEyes);
    	$writtenEyes['eyes'] = 0;
        $actual = $this->mysqlObject->getById('dogs', 2);
    	$this->assertEquals($writtenEyes['eyes'], $actual['eyes'], "Int was not cast");

    	$writtenEyes['eyes'] = "2";
    	$this->mysqlObject->insert($writtenEyes);
    	$writtenEyes['eyes'] = 2;
        $actual = $this->mysqlObject->getById('dogs', 3);
    	$this->assertEquals($writtenEyes['eyes'], $actual['eyes'], "Int was not cast from string to int");

    	$writtenBirth = $emptyDog;
    	$writtenBirth['birth'] = "2000 01 01";
    	$this->mysqlObject->insert($writtenBirth);
    	$writtenBirth['birth'] = "1970-01-01";
        $actual = $this->mysqlObject->getById('dogs', 4);
    	$this->assertEquals($writtenBirth['birth'], $actual['birth'], "Date was not cast");

    	$writtenBirth['birth'] = "3000-01-01";
    	$this->mysqlObject->insert($writtenBirth);
    	$writtenBirth['birth'] = "3000-01-01";
        $actual = $this->mysqlObject->getById('dogs', 5);
    	$this->assertEquals($writtenBirth['birth'], $actual['birth'], "Future was not cast");

    	$writtenBirth['birth'] = "1000-01-01";
    	$this->mysqlObject->insert($writtenBirth);
    	$writtenBirth['birth'] = "1000-01-01";
        $actual = $this->mysqlObject->getById('dogs', 6);
    	$this->assertEquals($writtenBirth['birth'], $actual['birth'], "Past was not cast");

    	$writtenBirth['birth'] = "1 01 01";
    	$this->mysqlObject->insert($writtenBirth);
    	$writtenBirth['birth'] = "1970-01-01";
        $actual = $this->mysqlObject->getById('dogs', 7);
    	$this->assertEquals($writtenBirth['birth'], $actual['birth'], "Start of time was cast, should have returned 1970");

    	$writtenBirth['birth'] = "2015-02-30";
    	$this->mysqlObject->insert($writtenBirth);
    	$writtenBirth['birth'] = "2015-03-02";
        $actual = $this->mysqlObject->getById('dogs', 8);
    	$this->assertEquals($writtenBirth['birth'], $actual['birth'], "Incorrect date was accepted");

    	$writtenSeen = $emptyDog;
    	$writtenSeen['lastSeen'] = "2000 01 01 00:00:00";
    	$this->mysqlObject->insert($writtenSeen);
    	$writtenSeen['lastSeen'] = "2000-01-01 00:00:00";
        $actual = $this->mysqlObject->getById('dogs', 9);
    	$this->assertEquals($writtenSeen['lastSeen'], $writtenSeen['lastSeen'], "Timestamp was not cast");

    	$writtenSeen['lastSeen'] = "1960 01 01 00:00:00";
    	$this->mysqlObject->insert($writtenSeen);
    	$writtenSeen['lastSeen'] = "1970-01-01 00:00:01";
        $actual = $this->mysqlObject->getById('dogs', 10);
    	$this->assertEquals($writtenSeen['lastSeen'], $writtenSeen['lastSeen'], "Timestamp before Unix 0 was not cast");

    	$writtenSeen['lastSeen'] = "2040 01 01 00:00:00";
    	$this->mysqlObject->insert($writtenSeen);
    	$writtenSeen['lastSeen'] = "1970-01-01 00:00:01";
        $actual = $this->mysqlObject->getById('dogs', 11);
    	$this->assertEquals($writtenSeen['lastSeen'], $writtenSeen['lastSeen'], "Timestamp after Unix -1 was not cast");

    	$writtenSeen['lastSeen'] = "2015 01 01 30:00:00";
    	$this->mysqlObject->insert($writtenSeen);
    	$writtenSeen['lastSeen'] = "1970-01-01 00:00:01";
        $actual = $this->mysqlObject->getById('dogs', 12);
    	$this->assertEquals($writtenSeen['lastSeen'], $writtenSeen['lastSeen'], "Incorrect hours were not cast");

    	$writtenSeen['lastSeen'] = "2015-01-01 00:70:00";
    	$this->mysqlObject->insert($writtenSeen);
    	$writtenSeen['lastSeen'] = "1970-01-01 00:00:01";
        $actual = $this->mysqlObject->getById('dogs', 12);
    	$this->assertEquals($writtenSeen['lastSeen'], $writtenSeen['lastSeen'], "Incorrect minutes were not cast");

    	$writtenSeen['lastSeen'] = "2015-01-01 00:00:70";
    	$this->mysqlObject->insert($writtenSeen);
    	$writtenSeen['lastSeen'] = "1970-01-01 00:00:01";
        $actual = $this->mysqlObject->getById('dogs', 13);
    	$this->assertEquals($writtenSeen['lastSeen'], $writtenSeen['lastSeen'], "Incorrect seconds were not cast");
    }

    /**
     * Tests the update function
     */
    public function testUpdate()
    {
        $values = array(
            'id' => 0,
            'name' => 'Fido',
            'eyes' => 2,
            'birth' => '2015-06-10',
            'lastSeen' => '2015-06-10 01:00:00',
            'quote' => 'Woef',
            'weight' => 10.0
        );
        $this->mysqlObject->insert($values);
        $values['name'] = "Goofy";
        $values['birth'] = "1932-05-25";
        $values['quote'] = "Gosh";
        $this->mysqlObject->insert($values);

        $expected = $values;
        $expected['id'] = 2;
        
        $actual = $this->mysqlObject->getById('dogs', 2);
        $this->assertEquals($expected, $actual, "The wrong dog was inserted");

        $update = array(
            'name' => 'Rex',
            'id' => 2
        );
        $updateResult = $this->mysqlObject->update($update, 'id');
        $this->assertTrue($updateResult, "The return value of update is wrong");
        $actual = $this->mysqlObject->getById('dogs', 2);
        $expected['name'] = 'Rex';
        $this->assertEquals($expected, $actual, "The wrong dog was updated, Fido is not Goofy");

        $update = array(
            'lastSeen' => 'NOW()',
            'id' => 2
        );
        $this->mysqlObject->update($update, 'id');
        $actual = $this->mysqlObject->getById('dogs', 2);

        $expectedNow = time();;
        $expectedMinus1 = date("Y-m-d H:i:s", $expectedNow-1);
        $expectedPlus1 = date("Y-m-d H:i:s", $expectedNow+1);
        $expectedNow = date("Y-m-d H:i:s", $expectedNow);

        // There can be a delay in the update.
        switch($actual['lastSeen']) {
            case $expectedMinus1:
                $expected['lastSeen'] = $expectedMinus1;
            break;
            case $expectedNow:
                $expected['lastSeen'] = $expectedNow;
            break;
            case $expectedPlus1:
                $expected['lastSeen'] = $expectedPlus1;
            break;
        }
        $this->assertEquals($expected, $actual, "The wrong dog was updated, timestamp was wrong");
    }

    /**
     * Tests the update function
     */
    public function testDelete()
    {
        $values = array(
            'id' => 0,
            'name' => 'Fido',
            'eyes' => 2,
            'birth' => '2015-06-10',
            'lastSeen' => '2015-06-10 01:00:00',
            'quote' => 'Woef',
            'weight' => 10.0
        );
        $this->mysqlObject->insert($values);
        $expected = $values;
        $expected['id'] = 1;
        $actual = $this->mysqlObject->getById('dogs', 1);
        $this->assertEquals($expected, $actual, "Wrong dog inserted");
        $this->mysqlObject->delete('id', 1);
        $actual = $this->mysqlObject->getById('dogs', 1);
        $this->assertNull($actual, "The dog was not deleted");

        $this->mysqlObject->insert($values);
        $this->mysqlObject->insert($values);
        $values['id'] = 4;
        $values['name'] = "Xzargl";
        $values['quote'] = "Take me to your intergalactic stickthrower boss";
        $values['eyes'] = 8;
        $this->mysqlObject->insert($values);
        //Make sure all 3 dogs exist
        $dog1 = $this->mysqlObject->getById('dogs', 2);
        $dog2 = $this->mysqlObject->getById('dogs', 3);
        $dog3 = $this->mysqlObject->getById('dogs', 4);
        $this->assertNotNull($dog1, "Dog 1 was not inserted");
        $this->assertNotNull($dog2, "Dog 2 was not inserted");
        $this->assertEquals($values, $dog3, "Wrong dog retrieved");

        //Delete all human dogs
        $this->mysqlObject->delete('eyes', 2);
        $dog1 = $this->mysqlObject->getById('dogs', 2);
        $dog2 = $this->mysqlObject->getById('dogs', 3);
        $dog3 = $this->mysqlObject->getById('dogs', 4);
        $this->assertNull($dog1, "Dog 1 was not deleted");
        $this->assertNull($dog2, "Dog 2 was not deleted");
        $this->assertEquals($values, $dog3, "Wrong dog deleted");

        //Multiply the alien dogs
        $values['id'] = 0;
        for($i=0;$i<5;$i++) {
            $this->mysqlObject->insert($values);
        }
        $dogs = $this->mysqlObject->getWholeTable('dogs');
        $this->assertEquals(count($dogs), 6, "Not enough dogs");
        $this->mysqlObject->delete('eyes', 8, 3);
        $dogs = $this->mysqlObject->getWholeTable('dogs');
        $this->assertEquals(count($dogs), 3, "Delete limit was not obeyed");

    }
}

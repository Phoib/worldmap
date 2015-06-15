<?php
class mysqlObjectTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobalsBlacklist = array('mysqlDB');

    private $mysqlLoader;
    private $mysqlObject;

    public function setUp()
    {
	global $mysqlDB;
        global $testdatabase;	
	$mysqlDB->changeDB($testdatabase);
        $this->mysqlLoader = new mysqlLoader();
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlObject.sql");
        $this->mysqlObject = new mysqlObject('dogs');
    }


    public function tearDown()
    {
        $this->mysqlLoader->dropTables();
        $this->mysqlObject = null;
    }

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

    public function testInitialInsert()
    {
	$expected = $this->mysqlObject->returnInitialArray();
        $expected['id'] = 1;
        $this->mysqlObject->insert($expected);
	$actual = $this->mysqlObject->getById('dogs', 1);
	foreach($expected as &$value) {
            $value = (string)$value;
	}
	$this->assertEquals($expected, $actual, "Empty gets inserted correctly");
    }

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
}

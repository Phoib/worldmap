<?php
class mysqlObjectTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobalsBlacklist = array('mysqlDB');

    private $mysqlLoader;
    private $mysqlObject;

    public function setUp()
    {
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
            'lastSeen' => '1970-01-01 01:00:00',
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
}

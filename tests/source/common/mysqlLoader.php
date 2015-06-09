<?php
class mysqlLoaderTest extends PHPUnit_Framework_TestCase
{
    protected $backupGlobalsBlacklist = array('mysqlDB');

    private $mysql;
    private $mysqlLoader;

    public function setUp()
    {
        global $mysqlDB;
        $this->mysql = $mysqlDB;
        $this->mysqlLoader = new mysqlLoader();
    }


    public function tearDown()
    {
        $this->mysqlLoader->dropTables();
    }

    public function testLoadSQLFile()
    {
	$result = $this->mysql->query("SHOW TABLES");
	$this->assertEquals(0, $result->num_rows, "Empty database is not empty!");

        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");
	$result = $this->mysql->query("SHOW TABLES");
	$this->assertEquals(1, $result->num_rows, "Database is now loaded!");
    }


    public function testDropTables()
    {
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");

	$result = $this->mysql->query("SHOW TABLES");
	$this->assertEquals(1, $result->num_rows, "Database is now loaded!");

        $this->mysqlLoader->dropTables();
	$result = $this->mysql->query("SHOW TABLES");
	$this->assertEquals(0, $result->num_rows, "Empty database is not empty!");
    }
}

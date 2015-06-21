<?php
/**
 * This class describes the MySQLLoader Test object, used to test the MySQLLoader class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class mysqlLoaderTest extends PHPUnit_Framework_TestCase
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
        $this->mysql = $mysqlDB;
    	$this->mysql->changeDB($testdatabase);
        $this->mysqlLoader = new mysqlLoader();
    }

    /**
     * Destroy the tests
     */
    public function tearDown()
    {
        $this->mysqlLoader->dropTables();
    }

    /**
     * Tests if an SQL file is correctly loaded and parsed
     */
    public function testLoadSQLFile()
    {
    	$result = $this->mysql->query("SHOW TABLES");
    	$this->assertEquals(0, $result->num_rows, "Empty database is not empty!");

        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");
    	$result = $this->mysql->query("SHOW TABLES");
    	$this->assertEquals(1, $result->num_rows, "Database is now loaded!");
    }

    /**
     * Tests if tables are dropped
     */
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

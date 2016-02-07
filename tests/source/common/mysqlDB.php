<?php

/**
 * This class describes the MySQL Test object, used to test the MySQLDB class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class mysqlDBTest extends PHPUnit_Framework_TestCase
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
     * Tests if an empty database exists
     */ 
    public function testEmptyTestDatabase()
    {
    	$result = $this->mysql->query("SHOW TABLES");

    	$this->assertEquals(0, $result->num_rows, "Empty database is not empty!");
    }

    /**
     * Tests the sanitation function
     */
    public function testSanitation()
    {
        $cleanQuery = "SELECT * FROM bla";
    	$actual = $this->mysql->sanitize($cleanQuery);
        $this->assertEquals($cleanQuery, $actual, "The clean query was not clean!");

        $semicolonQuery = "SELECT * FROM foo; SELECT * FROM bar;";
        $expected = "SELECT * FROM foo SELECT * FROM bar";
    	$actual = $this->mysql->sanitize($semicolonQuery);
        $this->assertEquals($expected, $actual, "Semi colon not cleaned!");

        $quoteQuery = "SELECT * FROM test WHERE foo = 'bar'";
        $expected = "SELECT * FROM test WHERE foo = &#039;bar&#039;";
    	$actual = $this->mysql->sanitize($quoteQuery);
        $this->assertEquals($expected, $actual, "Single quote not cleaned!");

        $doubleQuoteQuery = "SELECT * FROM test WHERE foo = \"bar\"";
        $expected = "SELECT * FROM test WHERE foo = &quot;bar&quot;";
    	$actual = $this->mysql->sanitize($doubleQuoteQuery);
        $this->assertEquals($expected, $actual, "Double quote not cleaned!");

        $htmlQuery = "SELECT * FROM test WHERE foo = <html>";
        $expected = "SELECT * FROM test WHERE foo = &lt;html&gt;";
    	$actual = $this->mysql->sanitize($htmlQuery);
        $this->assertEquals($expected, $actual, "HTML not cleaned!");
    }

    /**
     * Tests the SQL get functions
     */
    public function testGets()
    {
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");

        $expected = array('id' => 1, 'name' => 'adam');

        $singleRow = "SELECT * FROM persons WHERE id = 1";
        $actual = $this->mysql->getRow($singleRow);
        $this->assertEquals($expected, $actual, "Single row not fetched!");

        $actual = $this->mysql->getById('persons', 1);
        $this->assertEquals($expected, $actual, "GetById return wrong row!");

        $expected = array(
                        array('id' => 1, 'name' => 'adam'),
                        array('id' => 2, 'name' => 'eve'),
                        array('id' => 3, 'name' => 'emiel')
        );

        $allRows = "SELECT * FROM persons WHERE 1";
        $actual = $this->mysql->getRows($allRows);
        $this->assertEquals($expected, $actual, "All rows not fetched!");

        $actual = $this->mysql->getWholeTable('persons');
        $this->assertEquals($expected, $actual, "Whole table not fetched!");
    }

    /**
     * Tests the describe function
     */
    public function testDescribe()
    {
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");

        $expected = array(
            'id' => array(
                'Field' => 'id',
                'Type' => 'int(11)',
                'Null' => 'NO',
                'Key' => 'PRI',
                'Default' => NULL,
                'Extra' => 'auto_increment'
            ), 
            'name' => array(
                'Field' => 'name',
                'Type' => 'char(30)',
                'Null' => 'NO',
                'Key' => '',
                'Default' => NULL,
                'Extra' => ''
            )
        );

        $actual = $this->mysql->describeTable('persons');
        $this->assertEquals($expected, $actual, "Describe not correct!");
    }

    /**
     * Tests if describe function calls are logged
     */
    public function testLoggedDescribe()
    {
        $expected = array(
            'id' => array(
                'Field' => 'id',
                'Type' => 'int(11)',
                'Null' => 'NO',
                'Key' => 'PRI',
                'Default' => NULL,
                'Extra' => 'auto_increment'
            ), 
            'name' => array(
                'Field' => 'name',
                'Type' => 'char(30)',
                'Null' => 'NO',
                'Key' => '',
                'Default' => NULL,
                'Extra' => ''
            )
        );
        $actual = $this->mysql->describeTable('persons');
        $this->assertEquals($expected, $actual, "Describe not correct!");

    }

    /**
     * Tests if the SQL log has all entries
     */
    public function testSQLLog()
    {
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");
        $this->mysql->describeTable('persons');
        $allRows = "SELECT * FROM persons WHERE 1";
        $this->mysql->getRows($allRows);
        $this->mysql->getById('persons', 1);

        // ALL queries for this testclass are logged
        $expected = array(
            "query: [SET NAMES 'utf8']",
            "query: [SET NAMES 'utf8']",
            "query: [SHOW TABLES]",
            "query: [SET NAMES 'utf8']",
            "query: [SET NAMES 'utf8']",
            "query: [CREATE TABLE persons (
  id int NOT NULL AUTO_INCREMENT,
  name CHAR(30) NOT NULL,
  PRIMARY KEY (id)
)]",
            "query: [INSERT INTO persons (name) VALUES 
  ('adam'), ('eve'), ('emiel')]",
            "query: [SELECT * FROM persons WHERE id = 1]",
            "query: [SELECT * FROM persons WHERE id = 1]",
            "query: [SELECT * FROM persons WHERE 1]",
            "query: [SELECT * FROM persons WHERE 1]",
            "query: [DROP TABLE persons]",
            "query: [SET NAMES 'utf8']",
            "query: [CREATE TABLE persons (
  id int NOT NULL AUTO_INCREMENT,
  name CHAR(30) NOT NULL,
  PRIMARY KEY (id)
)]",
            "query: [INSERT INTO persons (name) VALUES 
  ('adam'), ('eve'), ('emiel')]",
            "query: [DESCRIBE persons]",
            "query: [DROP TABLE persons]",
            "query: [SET NAMES 'utf8']",
            "query: [SET NAMES 'utf8']",
            "query: [CREATE TABLE persons (
  id int NOT NULL AUTO_INCREMENT,
  name CHAR(30) NOT NULL,
  PRIMARY KEY (id)
)]",
            "query: [INSERT INTO persons (name) VALUES 
  ('adam'), ('eve'), ('emiel')]",

            "query: [SELECT * FROM persons WHERE 1]",
            "query: [SELECT * FROM persons WHERE id = 1]"
        );
        $actual = array();
        $fullLog = $this->mysql->getSQLLog();
        foreach($fullLog as $log) {
            $parts = explode(";", $log);
            $actual[] = trim($parts[2]);
        }
        $this->assertEquals($expected, $actual, "SQL Statements not logged!");
    }

    /**
     * Tests if the count function works
     */
    public function testCount()
    {
        $this->mysqlLoader->loadSQLFile("tests/sql/common/mysqlDB.sql");
        $this->mysql->describeTable('persons');
        $actual = $this->mysql->getCountOfTable('persons');
        $this->assertEquals($actual, 3, "The count was not correct!");
        $actual = $this->mysql->getCountOfTable('personsNotThere');
        $this->assertEquals($actual, 0, "The count of non existing table was not correct!");
    }
}

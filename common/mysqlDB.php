<?php

/**
 * This class describes the MySQL object, used to execute mysql queries
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   common
 * @package    worldmap
 * @subpackage Core
 */
class mysqlDB
{
    /**
     * @var \mysqli    MySQL connection
     */
    protected	$connection;

    /**
     * @var array    Array containg the SQL queries and performance parameters
     */
    private $sqlLog;

    /**
     * @var array    Array containing the table descriptions
     */
    private $descriptions;

    /**
     * Create the mysqlDB object, used for MySQL queries.
     *
     * @param string $host     Hostname of the mysql server
     * @param string $user     Username of the mysql connection
     * @param string $password Password to identify the user
     * @param string $database Database to be connected to
     */
    public function __construct($host, $user, $password, $database)
    {
	$this->sqlLog = array();
	$this->descriptions = array();

        $this->connection = new mysqli($host, $user, $password, $database);
        $this->query("SET NAMES 'utf8'");	
    }

    /**
     * Returns the SQL log
     *
     * @return array $sqlLog
     */
    public function getSQLLog()
    {
        return $this->sqlLog;
    }

    /**
     * Appends a query to the SQL log
     *
     * @param string SQL log statement
     */
    public function appendToLog($statement)
    {
        $this->sqlLog[] = $statement;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function setFromGlobalDB()
    {
        global $mysqlDB;
        $this->setConnection($mysqlDB->getConnection());
    }

    public function changeDB($database)
    {
        $this->connection->select_db($database);
        $this->query("SET NAMES 'utf8'");
    }

    public function query ( $sql = false ) {
        $now = microtime(true);
        $memory = memory_get_usage(true);
        $res = $this->connection->query($sql);
        $now = (int)((microtime(true) - $now)*1000000);
        $memory = (int)((memory_get_usage(true) - $memory)/1024);
	$statement = "SQL Query: $now usec; $memory Kb; query: [$sql]";
        if(get_class($this) == "mysqlDB") {
            $this->appendToLog($statement);
        } else{
            global $mysqlDB;
            $mysqlDB->appendToLog($statement);
        }
        return (isset($res)) ? $res : false;
    }

    public function getRow($sql = false)
    {
        $result = $this->query($sql);
        if($result) {
            return $result->fetch_assoc();
        }
        return false;
    }   

    public function getRows($sql = false)
    {
        $result = $this->query($sql);
        if($result) {
            $return = array();
            while ($row = $result->fetch_assoc()) {
                $return [] = $row;
            }
            $result->free();
            return $return;
        }
        return false;
    }   

    public function getById($table, $id)
    {
        $sql = $this->sanitize(sprintf("SELECT * FROM %s WHERE id = %d", $table, $id));
        return $this->getRow($sql);
    }

    public function getWholeTable($table)
    {
        $sql = $this->sanitize(sprintf("SELECT * FROM %s WHERE 1", $table));
        return $this->getRows($sql);
    }

    public function describeTable($table)
    {
	if(isset($this->descriptions[$table])) {
            return $this->descriptions[$table];
        }
        $sql = $this->sanitize(sprintf("DESCRIBE %s", $table));
        $rows = $this->getRows($sql);
        $description = array();
        foreach($rows as $row) {
            $description[$row['Field']] = $row;
        }
        $this->descriptions[$table] = $description;
        return $this->descriptions[$table];
    }

    public function sanitize ($sql = false)
    {
        $sql = str_replace(";", "", $sql);
        $sql = htmlspecialchars($sql, ENT_QUOTES);
        return $this->connection->real_escape_string($sql);
    }
}

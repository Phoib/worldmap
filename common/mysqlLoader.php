<?php

/**
 * This class loads SQL files, for tests and installers
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   common
 * @package    worldmap
 * @subpackage Core
 */
class mysqlLoader extends mysqlDB
{
    /**
     * @var array Array containing the names of created tables
     */
    private $createdTables = array();

    /**
     * Constructs a mysqlLoader object, by getting a global DB connection.
     * This is needed for the phpunit tests
     */
    public function __construct()
    {
        $this->setFromGlobalDB();
    }

    /**
     * Executes all queries from a SQL file
     *
     * @param string $file Filename with the SQL statements.
     */
    public function loadSQLFile($file)
    {
        if(substr($file, -4) != ".sql") {
            throw new Exception("Supplied file is not a SQL File");
        }
        $sql = file_get_contents($file);
        $sqlStatements = explode(";", $sql);
        foreach($sqlStatements as $sqlStatement) {
            $sqlStatement = trim($sqlStatement);
            if(strtoupper(substr($sqlStatement, 0, 12)) == "CREATE TABLE") {
                $createStatement = explode(" ", $sqlStatement);
                $this->createdTables[] = $createStatement[2];
            }
            if(!empty($sqlStatement)) {
                $this->query($sqlStatement);
            }
        }
    }

    /**
     * Drops all tables from the database
     */
    public function dropTables() 
    {
        foreach($this->createdTables as $table) {
            $sql = "DROP TABLE $table";
            $this->query($sql);
        }
    }
}

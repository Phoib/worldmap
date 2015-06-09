<?php

class mysqlLoader extends mysqlDB
{
    private $createdTables = array();

    public function __construct()
    {
        $this->setFromGlobalDB();
    }

    public function loadSQLFile($file)
    {
        if(substr($file, -4) != ".sql") {
            throw new Exception("Supplied file is not a SQL File");
        }
        $sql = file_get_contents($file);
        $sqlStatements = explode(";", $sql);
        foreach($sqlStatements as $sqlStatement) {
            trim($sqlStatement);
            if(strtoupper(substr($sqlStatement, 0, 12)) == "CREATE TABLE") {
                $createStatement = explode(" ", $sqlStatement);
                $this->createdTables[] = $createStatement[2];
            }
            $this->query($sqlStatement);
        }
    }

    public function dropTables() 
    {
        foreach($this->createdTables as $table) {
            $sql = "DROP TABLE $table";
            $this->query($sql);
        }
    }
}

<?php

class mysqlDB
{
    private $connection;

    public function __construct($host, $user, $password, $database)
    {
        $this->connection = new mysqli($host, $user, $password, $database);
        $this->connection->query("SET NAMES 'utf8'");	
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
        $res = $this->connection->query($sql);
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
        $sql = $this->sanitize(sprintf("DESCRIBE %s", $table));
        return $this->getRows($sql);
    }

    public function sanitize ($sql = false)
    {
        $sql = str_replace(";", "", $sql);
        $sql = htmlspecialchars($sql, ENT_QUOTES);
        return $this->connection->real_escape_string($sql);
    }
}

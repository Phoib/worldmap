<?php

class mysqlObject extends mysqlDB
{
    private $objectName = "";
    private $description = NULL;

    public function __construct($name)
    {
        $this->setFromGlobalDB();
        $this->objectName = $name;
        $this->description = $this->describeTable($name);
        if(!$this->description) {
            throw new Exception("Table $name does not exist!");
        }
    }

    public function returnInitialArray()
    {
        $return = array();
        foreach($this->description as $row) {
            $initValue = $this->parseType('', $row['Type']);
            $return[$row['Field']] = $initValue;
        }
        return $return;
    }

    public function insert($values)
    {
        $insertValues = array();
        foreach($values as $key => $value) {
            $parsedValue = $this->parseType($value, $this->description[$key]['Type']);
            $insertValues[$key] = $parsedValue;
        }        
        $sql = "INSERT INTO `%s` ( %s ) VALUES ( %s ) ";
        $fields = array();
        $values = array();
        foreach($insertValues as $key => $value) {
            $fields[] = "`$key`";
            $values[] = "'$value'";
        }
        $sql = sprintf($sql, $this->objectName, implode(",", $fields),  implode(",", $values));
        $result = $this->query($sql);
        if(!$result) {
            return 0;
        }
        return $this->connection->insert_id;
    }

    private function parseType($value, $type)
    {
        switch($type) {
            case 'tinyint(1)':
            case 'tinyint(4)':
            case 'int(1)':
            case 'int(11)':
            case 'bigint(20)':
                return $this->parseInt($value);
            break;
            case 'double':
                return $this->parseDouble($value);
            break;
            case 'char(7)':
            case 'varchar(7)':
            case 'char(8)':
            case 'varchar(8)':
            case 'char(15)':
            case 'varchar(15)':
            case 'char(16)':
            case 'varchar(16)':
            case 'char(30)':
            case 'varchar(30)':
            case 'char(31)':
            case 'varchar(31)':
            case 'char(32)':
            case 'varchar(32)':
            case 'char(63)':
            case 'varchar(63)':
            case 'char(64)':
            case 'varchar(64)':
            case 'char(127)':
            case 'varchar(127)':
            case 'char(255)':
            case 'varchar(255)':
                return $this->parseChar($value, $type);
            break;
            case 'timestamp':
                return $this->parseTimestamp($value);
            break;
            case 'date':
                return $this->parseDate($value);
            break;
            case 'text':
            default:
                return $this->sanitize($value);
            break;
        }
    }

    private function parseInt($value)
    {
        return (int) $value;   
    }

    private function parseDouble($value)
    {
        return (double) $value;   
    }

    private function parseTimestamp($value)
    {
        return date(
            "Y-m-d H:i:s",
            strtotime($value)
        );
    }

    private function parseDate($value)
    {
        return date(
            "Y-m-d",
            strtotime($value)
        );
    }

    private function parseChar($value, $type)
    {
        if(empty($value)) {
            return "";
        }
        $startParenthesis = strpos($type, "(");
        $endParenthesis = strpos($type, ")");
        if($startParenthesis !== false &&
           $endParenthesis !== false) {
            $length = (int)substr($type, $startParenthesis+1, $endParenthesis - $startParenthesis-1);
            $value = substr($value, 0, $length);
            return $this->sanitize($value);
        }
        return $this->sanitize($value);
    }

}

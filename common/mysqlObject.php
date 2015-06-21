<?php

/**
 * This class describes the MySQLObject object, used to do operations on tables
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   common
 * @package    worldmap
 * @subpackage Core
 */
class mysqlObject extends mysqlDB
{
    /**
     * @var string $objectName  Name of the current object
     */
    private $objectName = "";

    /**
     * @var array $description  Description of the corresponding mysqlTable
     */
    private $description = NULL;

    /**
     * Construct a MySQL object, based on a Mysql table name
     *
     * @var string $name    The name of the table to base the MySQL object on
     * @throws Exception    If the table does not exist, throws an Exception
     */
    public function __construct($name)
    {
        $this->setFromGlobalDB();
        $this->objectName = $name;
        $this->description = $this->describeTable($name);
        if(!$this->description) {
            throw new Exception("Table $name does not exist!");
        }
    }

    /**
     * Return an array, with empty values, based on the MySQL description
     *
     * @return array    Array with the empty description
     */
    public function returnInitialArray()
    {
        $return = array();
        foreach($this->description as $row) {
            $initValue = $this->parseType('', $row['Type']);
            $return[$row['Field']] = $initValue;
        }
        return $return;
    }

    /**
     * Insert an Array into MySQL, parsing all values
     *
     * @param array $values Array with the values for the database
     * @return int          Returns the ID of the newly created entry
     */
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

    /**
     * Parses a value, based on the MySQL type
     *
     * @param mixed  $value The value to be parsed
     * @param string $type  The type to check against
     * @return mixed        The parsed value
     */
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

    /**
     * Casts a value to an int
     *
     * @param mixed $value The value to be cast
     * @return int         The cast value
     */
    private function parseInt($value)
    {
        return (int) $value;   
    }

    /**
     * Casts a value to an double
     *
     * @param mixed $value The value to be cast
     * @return double      The cast value
     */
    private function parseDouble($value)
    {
        return (double) $value;   
    }

    /**
     * Casts a value to a MySQL timestamp
     *
     * @param mixed $value The value to be cast
     * @return string      The cast value
     */
    private function parseTimestamp($value)
    {
	$timeValue = strtotime($value);
	if($timeValue == 0) {
	    $timeValue = 1;
        }
        return date(
            "Y-m-d H:i:s",
            $timeValue
        );
    }

    /**
     * Casts a value to a MySQL date
     *
     * @param mixed $value The value to be cast
     * @return string      The cast value
     */
    private function parseDate($value)
    {
        return date(
            "Y-m-d",
            strtotime($value)
        );
    }

    /**
     * Casts a value to a string
     *
     * @param mixed $value The value to be cast
     * @return string      The cast value
     */
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

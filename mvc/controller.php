<?php

/**
 * This class describes the Controller object, used to provide DB access to the Model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class controller extends mysqlObject
{
    /**
     * @var array $collection
     */
    private $collection = array();

    /**
     * Construct a controller object, based on a mysqlObject
     *
     * @var string $name    The name of the table to base the MySQL object on
     * @throws Exception    If the table does not exist, throws an Exception
     */
    public function __construct($name = false)
    {
        $this->setFromGlobalDB();
        if($name) {
            $this->objectName = $name;
            $this->description = $this->describeTable($name);
            if(!$this->description) {
                throw new Exception("Table $name does not exist!");
            }
        }
    }

    /**
     * Adds an object to the collection
     *
     * @param \mysqlObject $mysqlObject
     */
    public function addToCollection($mysqlObject)
    {
        $this->collection[] = $mysqlObject;
    }

    /**
     * Returns the entire collection
     *
     * @return array $collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Resets the collection
     */
    public function resetCollection()
    {
        $this->collection = array();
    }
}

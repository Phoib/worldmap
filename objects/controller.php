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
    public function returnCollection()
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

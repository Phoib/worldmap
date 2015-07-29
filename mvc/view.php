<?php

/**
 * This class describes the View object, used to provide HTML output capabilities to the Model
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   objects
 * @package    worldmap
 * @subpackage mvc
 */
class view extends html
{

    /**
     * Sets the title
     *
     * @param string $title The title for the HTML page
     */
    public function setTitle($title)
    {
        $this->head->setTitle($title);
    }
}

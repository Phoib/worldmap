<?php

/**
 * This class describes the HTML object, used to output HTML
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   common
 * @package    worldmap
 * @subpackage Core
 */
class htmlHead extends html
{

    private $title = "";

    public function __construct()
    {
    }

    /**
     * Gets the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Sets the title
     *
     * @param string $title The title for the HTML page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Renders the html for the head
     *
     * @return string The rendered html
     */
    public function render()
    {
        $html = sprintf("  <head>\n    <title>%s</title>\n  </head>\n",
            $this->title);
        return $html;
    }

}

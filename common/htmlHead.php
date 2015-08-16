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

    /**
     * @var string Title of the HTML page
     */
    private $title;

    /**
     * @var string Holds printable javascript code
     */
    private $printableJavascript;

    public function __construct()
    {
        $this->title = "";
        $this->printableJavascript = false;
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
     * Gets the javascript
     *
     * @return string
     */
    public function getJavascript()
    {
        return $this->printableJavascript;
    }

    /**
     * Sets the javascript
     *
     * @param string $javascript The entire javascript for the HTML page
     */
    public function setJavascript($javascript)
    {
        $this->printableJavascript = $javascript;
    }

    /**
     * Adds javascript
     *
     * @param string $javascript The javascript to be added
     */
    public function addJavascript($javascript)
    {
        $this->printableJavascript .= $javascript;
    }

    /**
     * Renders the html for the head
     *
     * @return string The rendered html
     */
    public function render()
    {
        $html = sprintf("  <head>\n    <title>%s</title>\n",
            $this->title);
        if($this->printableJavascript) {
            $html .= sprintf("    <script type='text/javascript'>\n%s\n    </script>\n", 
                $this->printableJavascript);
        }
        $html .= "  </head>\n";
        return $html;
    }

}

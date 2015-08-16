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

    /**
     * Gets the title
     *
     * @return string $title The title for the HTML page
     */
    public function getTitle()
    {
        return $this->head->getTitle();
    }

    /**
     * Sets the javascript
     *
     * @param string $javascript The javascript for the HTML page
     */
    public function setJavascript($javascript)
    {
        $this->head->setJavascript($javascript);
    }

    /**
     * Sets the javascript
     *
     * @param string $javascript The javascript for the HTML page
     */
    public function addJavascript($javascript)
    {
        $this->head->addJavascript($javascript);
    }

    /**
     * Gets the javascript
     *
     * @return string $javascript The javascript for the HTML page
     */
    public function getJavascript()
    {
        return $this->head->getJavascript();
    }

    /**
     * Redirects the browser to another page. Kills execution.
     *
     * @param string    $url    URL to redirect to
     */
    public function redirect($url)
    {
        header("Location: $url");
        die();
    }
}

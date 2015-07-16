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
class html
{
    /**
     * @private string $doctype     Defines the doctype
     */
    private $docType;

    /**
     * @private string $language    Defines the language of the html document
     */
    private $language;

    /**
     * @private \htmlHead $head     Object that contains all info for the head section
     */
    public $head = NULL;

    /**
     * @private \htmlChunk $body    Object that contains the contents of the body section
     */
    public $body = NULL;

    /**
     * @private string $html        String to contain all the HTML
     */
    private $html = "";

    /**
     * Constructs the HTML object
     */
    public function __construct()
    {
        $this->docType = "html";
        $this->language = "en";
        $this->head = new htmlHead();
        $this->body = new htmlChunk(htmlChunk::BODY);
    }

    /**
     * Returns the docType
     *
     * @return string $docType
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets the docType
     *
     * @param string $docType
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
    }

    /**
     * Returns the language
     *
     * @return string $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Returns the headObject
     *
     * @return \htmlHead 
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Sets the headObject
     *
     * @param \htmlHead $head
     */
    public function setHead($head)
    {
        $this->head = $head;
    }

    /**
     * Returns the bodyArray
     *
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the html array
     *
     * @param array $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Adds html to body array
     *
     * @param \htmlChunk|Array  html Mixed, adds HTML to body
     * @param string|bool       name Optional, name to store it at
     */
    public function addHtml($html, $name = false)
    {
        if($name === false || empty($name)) {
            $this->body->addHtml($html);
        } else{
            $this->body->addHtml($html, $name);
        }
    }

    /**
     * Returns the html string
     *
     * @return string $html
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Sets the html
     *
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * Puts the HTML in the html variable
     */
    public function render()
    {
        $this->html = sprintf("<!DOCTYPE %s>\n<html lang='%s'>\n", 
            $this->docType, $this->language);
        $this->html .= $this->head->render();
        $this->html .= $this->body->render(1);
        $this->html .= "</html>";
    }
}

<?php

/**
 * This class describes the HTML chunk object, used to output HTML
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   common
 * @package    worldmap
 * @subpackage Core
 */
class htmlChunk extends html
{

    const BODY = "body";
    const TABLE = "table";
    const FORM = "form";
    const TABLEROW = "tr";
    const TABLEHEADER = "th";
    const TABLECELL = "td";

    /**
     * @var string Type of the HTML chunk
     */
    private $type = "";

    /**
     * @var array Settings of html tag
     */
    private $settings = array();

    /**
     * @var array Contents of the HTML
     */
    private $contents = array();

    public function __construct($type, $settings = false)
    {
        $this->type = $type;
        if($settings) {
            $this->settings = $settings;
        }
    }

    /**
     * Gets the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type The type of the HTML chunk
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function addHtml($html, $name = false)
    {
        if($name === false || empty($name)) {
            $this->contents[] = $html;
        } else{
            $this->contents[$name] = $html;
        }
    }

    /**
     * Renders the html for the chunk
     *
     * @return string The rendered html
     */
    public function render($indentLevel)
    {
        $indentation = "";
        for($i=0;$i<$indentLevel;$i++) {
            $indentation .= "  ";
        }
        $settings = "";
        foreach($this->settings as $key => $value) {
            $settings .= " $key='$value'";
        }
        $html = sprintf("%s<%s%s>\n", $indentation, $this->type, $settings);
        foreach($this->contents as $content) {
            if(is_object($content)) {
                $html .= $content->render($indentLevel+1);
            } else{
                $html .= $indentation . "  " . $content . "\n";
            }
        }
        $html .= sprintf("%s</%s>\n", $indentation, $this->type);
        return $html;
    }

    /**
     * Generate a htmlChunk with type table from an array, using the contents
     * of the array. A 2d array is expected, but 1d is accepted.
     *
     * @param array $array  2d array with table contents
     * @param bool  $header Optional parameter, defines if the first row should be a header
     * @return \htmlChunk   A table htmlChunk
     */
    public static function generateTableFromArray($array, $header = false)
    {
        $table = new htmlChunk(htmlChunk::TABLE);
        foreach($array as $row) {
            $rowChunk = new htmlChunk(htmlChunk::TABLEROW);
            if($header) {
                foreach($row as $cell) {
                    $cellChunk = new htmlChunk(htmlChunk::TABLEHEADER);
                    $cellChunk->addHtml($cell);
                    $rowChunk->addHtml($cellChunk);
                }
                $header = false;
            } else{
                foreach($row as $cell) {
                    $cellChunk = new htmlChunk(htmlChunk::TABLECELL);
                    $cellChunk->addHtml($cell);
                    $rowChunk->addHtml($cellChunk);
                }
             }
            $table->addHtml($rowChunk);       
        }
        return $table;
    }

    /**
     * Generate a form chunk
     */
    public static function generateForm($action, $get = false)
    {
        $settings = array(
            'action' => $action,
            'method' => 'post'
        );
        if($get) {
            $settings['method'] = 'get';
        }
        $form = new htmlChunk(htmlChunk::FORM, $settings);
        return $form;
    }
}

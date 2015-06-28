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
    const TABLEROW = "tr";
    const TABLEHEADER = "th";
    const TABLECELL = "td";

    /**
     * @var string Type of the HTML chunk
     */
    private $type = "";

    /**
     * @var array Contents of the HTML
     */
    private $contents = array();

    public function __construct($type)
    {
        $this->type = $type;
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
        $html = sprintf("%s<%s>\n", $indentation, $this->type);
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
}

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

    const BODY        = "body";
    const TABLE       = "table";
    const FORM        = "form";
    const INPUT       = "input";
    const TABLEROW    = "tr";
    const TABLEHEADER = "th";
    const TABLECELL   = "td";
    const LINK        = "a";
    const SELECT      = "select";
    const OPTION      = "option";
    const INPUTTYPES  = array(
        'text',
        'button',
        'checkbox',
        'file',
        'hidden',
        'password',
        'radio',
        'reset',
        'submit',
        'text'
    );

    /**
     * @var string Type of the HTML chunk
     */
    private $type = "";

    /**
     * @var string Name of the HTML chunk
     */
    private $name = "";

    /**
     * @var string ID of the HTML chunk
     */
    private $id = "";

    /**
     * @var array Settings of html tag
     */
    private $settings = array();

    /**
     * @var array Contents of the HTML
     */
    private $contents = array();

    /**
     * @var bool If the html type has a closing tag
     */
    private $closingTag = true;

    public function __construct($type, $name = false, $id = false, $settings = false)
    {
        $this->type = $type;
        if($type == htmlChunk::INPUT) {
            $this->closingTag = false;
        }
        if($name) {
            $this->name = $name;
        }
        if($id) {
            $this->id = $id;
        }
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

    /**
     * Gets the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $type The name of the HTML chunk
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the id
     *
     * @return string
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Sets the id
     *
     * @param string $id The id of the HTML chunk
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Adds html under this htmlChunk
     *
     * @param mixed     The html to be added
     * @param string    The name for this html
     */
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
    public function render($indentLevel = 0)
    {
        $indentation = "";
        for($i=0;$i<$indentLevel;$i++) {
            $indentation .= "  ";
        }
        $settings = "";
        if($this->name) {
            $settings .= " name='" . $this->name . "'";
        }
        if($this->id) {
            $settings .= " id='" . $this->id . "'";
        }
        foreach($this->settings as $key => $value) {
            $settings .= " $key='$value'";
        }
        $html = sprintf("%s<%s%s>\n", 
            $indentation, $this->type, $settings);
        foreach($this->contents as $content) {
            if(is_object($content)) {
                $html .= $content->render($indentLevel+1);
            } elseif(is_array($content)) {
                foreach($content as $contentPiece) {
                    if(is_object($contentPiece)) {
                        $html .= $contentPiece->render($indentLevel+1);
                    } else{
                        $html .= $indentation . "  " . $contentPiece . "\n";
                    }
                }
            } else{
                $html .= $indentation . "  " . $content . "\n";
            }
        }
        if($this->closingTag) {
            $html .= sprintf("%s</%s>\n", $indentation, $this->type);
        }
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
    public static function generateTableFromArray($array, $header = false, $border = false)
    {
        $settings = array();
        if($border) {
            $settings['border'] = 1;
        }
        $table = new htmlChunk(htmlChunk::TABLE, false, false, $settings);
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
     *
     * @param string $action    Action to be done by the form
     * @param string $name      Name of the form
     * @param string $id        Id of the form
     * @param bool   $get       Optional, if the form should be (default) post or optional get
     * @return \htmlChunk       The htmlChunk with the form
     */
    public static function generateForm($action, $name, $id, $get = false)
    {
        $settings = array(
            'action' => $action,
            'method' => 'post'
        );
        if($get) {
            $settings['method'] = 'get';
        }
        return new htmlChunk(htmlChunk::FORM, $name, $id, $settings);
    }

    /**
     * Generate an input chunk
     *
     * @param string $type      Type of the input field
     * @param string $name      Name of the input field
     * @param string $id        Id of the input field
     * @param bool   $get       Optional, value of the input field
     * @return \htmlChunk       The htmlChunk with the input field

     */
    public static function generateInput($type, $name, $id, $value = false)
    {
        $type = strtolower($type);
        if(!in_array($type, htmlChunk::INPUTTYPES)) {
            throw new Exception("Invalid type declared: $type");
        }
        $settings = array(
            'type' => $type
        );
        if($value) {
            $settings['value'] = $value;
        }
        return new htmlChunk(htmlChunk::INPUT, $name, $id, $settings);
    }

    /**
     * Generate a HTML Link
     *
     * @param string    $url    The address to link to
     * @param string    $text   The text of the link
     * @return \htmlChunk       The htmlChunk with the hyperlink
     */
    public static function generateLink($url, $text)
    {
        $settings = array(
            'href' => $url
        );
        $link = new htmlChunk(htmlChunk::LINK, false, false, $settings);
        $link->addHtml($text);
        return $link;
    }

    /**
     * Generate a base URL
     */
    public static function generateBaseUrl()
    {
        $indexPos = strpos($_SERVER["PHP_SELF"], "index.php");
        $base = substr($_SERVER["PHP_SELF"], 0, $indexPos);
        $protocol = $_SERVER['REQUEST_SCHEME'] . "://";
        $url = $_SERVER['SERVER_NAME'] . $base . "/index.php/";
        $url = str_replace("//", "/", $url);
        $baseUrl = $protocol . $url;
        return $baseUrl;
    }

    /**
     * Generates a select box
     */
    public static function generateSelect($name, $id, $options, $onchange = false)
    {
        $settings = array();
        if($onchange) {
            $settings['onchange'] = $onchange;
        }
        $select = new htmlChunk(htmlChunk::SELECT, $name, $id, $settings);
        $htmlOptions = array();
        foreach($options as $name => $value) {
            $htmlOptions[] = self::generateOption($name, $value);
        }
        $select->addHtml($htmlOptions);
        return $select;
    }

    /**
     * Generates an option field
     */
    public static function generateOption($name, $value=false)
    {
        $settings = array();
        if($value) {
            $settings = array('value' => $value);
        }
        $option = new htmlChunk(htmlChunk::OPTION, false, false, $settings);
        $option->addHtml($name);
        return $option;
    }
}

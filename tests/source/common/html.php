<?php

/**
 * This class describes the html Test object, used to test the html class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class htmlTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests the set and get functions
     */
    public function testSetGet()
    {
        $html = new html();

        $docType = "html";
        $actual = $html->getDocType();
        $this->assertEquals($docType, $actual, "HTML docType was not html!");

        $language = 'HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"';
        $html->setDocType($docType);
        $actual = $html->getDocType();
        $this->assertEquals($docType, $actual, "DocType was not set!");

        $language = "en";
        $actual = $html->getLanguage();
        $this->assertEquals($language, $actual, "HTML does not have an english language!");

        $language = "nl";
        $html->setLanguage($language);
        $actual = $html->getLanguage();
        $this->assertEquals($language, $actual, "Language was not set!");

        $head = $html->getHead();
        $headType = get_class($head);
        $this->assertEquals("htmlHead", $headType, "It was not a htmlHead object!");

        $title = "Worldmap";
        $head->setTitle($title);
        $html->setHead($head);
        $actual = $html->head->getTitle();
        $this->assertEquals($title, $actual, "HTML head was not set!");

        $actual =  $html->getBody()->getType();
        $expected = htmlChunk::BODY;
        $this->assertEquals($expected, $actual);

        // set test for when we have actual html chunks

        // add html test for when we have actual html chunks

        $htmlContents = $html->getHtml();
        $this->assertEquals("", $htmlContents, "HTML contents are not empty!");

        $test = "foobar";
        $html->setHtml($test);
        $htmlContents = $html->getHtml();
        $this->assertEquals($test, $htmlContents, "HTML contents are not set!");
    }

    /**
     * Tests the render functionality
     */
    public function testRender()
    {
        $html = new html();

        $expected = "<!DOCTYPE html>\n<html lang='en'>\n  <head>\n    <title></title>\n  </head>\n  <body>\n  </body>\n</html>";
        $html->render();
        $htmlContents = $html->getHtml();
        $this->assertEquals($expected, $htmlContents, "HTML not properly rendered");
    }

}

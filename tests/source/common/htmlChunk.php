<?php

/**
 * This class describes the htmlHead Test object, used to test the htmlHead class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class htmlChunkTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests the set and get functions
     */
    public function testSetGet()
    {
        $htmlChunk = new htmlChunk(htmlChunk::BODY);
        $actual = $htmlChunk->getType();
        $this->assertEquals(htmlChunk::BODY, $actual, "HTML Chunk not set initially!");

        $htmlChunk->setType(htmlChunk::TABLE);
        $actual = $htmlChunk->getType();
        $this->assertEquals(htmlChunk::TABLE, $actual, "HTML Chunk not changed!");
    }

    /**
     * Tests the render functionality
     */
    public function testRender()
    {
        $htmlChunk = new htmlChunk(htmlChunk::BODY);
        $actual = $htmlChunk->render(0);
        $expected = "<body>\n</body>\n";
        $this->assertEquals($expected, $actual, "HTML body not rendered properly!");
        $actual = $htmlChunk->render(1);
        $expected = "  <body>\n  </body>\n";
        $this->assertEquals($expected, $actual, "HTML indentation not rendered properly!");
    }

    /**
     * Tests the render functionality
     */
    public function testAddHtml()
    {
        $body = new htmlChunk(htmlChunk::BODY);
        $table = new htmlChunk(htmlChunk::TABLE);
        $body->addHtml($table);
        $actual = $body->render(0);
        $expected = "<body>\n  <table>\n  </table>\n</body>\n";
        $this->assertEquals($expected, $actual, "HTML contents not rendered properly!");
    }

    /**
     * Test the tableFromArray functionality
     */
    public function testTableFromArray()
    {
        $row1 = array(1, 2);
        $row2 = array("foo", "bar");
        $table = array($row1, $row2);
        $html = htmlChunk::generateTableFromArray($table);
        $expected = "<table>\n  <tr>\n    <td>\n      1\n    </td>\n    <td>\n      2\n    </td>\n  </tr>\n  <tr>\n    <td>\n      foo\n    </td>\n    <td>\n      bar\n    </td>\n  </tr>\n</table>\n";
        $actual = $html->render(0);
        $this->assertEquals($expected, $actual, "Table not rendered properly!");

        $html = htmlChunk::generateTableFromArray($table, true);
        $expected = "<table>\n  <tr>\n    <th>\n      1\n    </th>\n    <th>\n      2\n    </th>\n  </tr>\n  <tr>\n    <td>\n      foo\n    </td>\n    <td>\n      bar\n    </td>\n  </tr>\n</table>\n";
        $actual = $html->render(0);
        $this->assertEquals($expected, $actual, "Table header not rendered properly!");
    }

    /**
     * Test the generateForm functionality
     */
    public function testForm()
    {
        $form = htmlChunk::generateForm('foo', 'bar', 'test');
        $actual = $form->render(0);
        $expected = "<form name='bar' id='test' action='foo' method='post'>\n</form>\n";
        $this->assertEquals($expected, $actual, "Form not rendered properly!");

        $form = htmlChunk::generateForm('foo', 'bar', 'test', true);
        $actual = $form->render(0);
        $expected = "<form name='bar' id='test' action='foo' method='get'>\n</form>\n";
        $this->assertEquals($expected, $actual, "Form with get not rendered properly!");
    }

    /**
     * Test the generateInput functionality
     */
    public function testInput()
    {
        foreach(htmlChunk::INPUTTYPES as $type) {
            $input = htmlChunk::generateInput($type, 'foo', 'bar');
            $actual = $input->render();
            $expected = "<input name='foo' id='bar' type='$type'>\n";
            $this->assertEquals($expected, $actual, "Input $type not rendered properly!");
        }

        $this->setExpectedException("Exception", "Invalid type declared: bla");
        $input = htmlChunk::generateInput('bla', 'foo', 'bar');      
    }

    /**
     * Test the generate link functionality
     */
    public function testLink()
    {
        $link = htmlChunk::generateLink("test.php", "Test");
        $actual = $link->render();
        $expected = "<a href='test.php'>\n  Test\n</a>\n";
        $this->assertEquals($expected, $actual, "Link does not work");
    }

    /**
     * Test the generate base url functionality
     */
    public function disabled_testBaseUrl()
    {
        //Disabled until a better mechanism is found, eg storing in config
    }
}

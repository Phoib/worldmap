<?php

/**
 * This class describes the phpUnit Test object, used to test the phpUnit class
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   tests
 * @package    worldmap
 * @subpackage common
 */
class phpUnitTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests if phpUnit exists
     */
    public function testSelfExistance()
    {
        $phpUnit = new phpUnit("tests/source");

        $testCases = $phpUnit->returnTests();

        $this->assertArrayHasKey("tests/source/common/phpunit.php", $testCases, "Test does not exist");
    }

    /**
     * Tests if testcases can be found
     */
    public function testFindTests()
    {
        $expected = array(
            "tests/testfiles/common/phpunit/bla.php",
            "tests/testfiles/common/phpunit/common/bar.php",
            "tests/testfiles/common/phpunit/common/foo.php"
        );

        $phpUnit = new phpUnit("tests/testfiles/common/phpunit");
        $testCases = $phpUnit->returnTests();
        $actual = array_keys($testCases);

        $this->assertEquals($expected, $actual, "Found test cases differ!");
    }
}

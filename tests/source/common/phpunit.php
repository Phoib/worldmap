<?php
class phpUnitTest extends PHPUnit_Framework_TestCase
{

    public function testSelfExistance()
    {
        $phpUnit = new phpUnit("tests/source");

        $testCases = $phpUnit->returnTests();

        $this->assertArrayHasKey("tests/source/common/phpunit.php", $testCases, "Test does not exist");
    }

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

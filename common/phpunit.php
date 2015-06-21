<?php
/**
 * Class making the autotesting of PHP code possible in this project
 *
 * @author     Emiel Suilen
 * @copyright  Derelict Studios
 * @category   common
 * @package    worldmap
 * @subpackage Core
 */
class phpUnit
{
    /**
     * @var string $command Commandline parameter to start phpunit
     */
    private $command = "/usr/bin/php common/phpunit.phar";

    /**
     * @var string $versionInfo string to save the versionInfo, to be removed
     */
    private $versionInfo = "";

    /**
     * @var array Array with empty testcases
     */
    private $testCases = array();

    /**
     * Construct the phpUnit class, based on a directory
     *
     * @param string $directory Directory to scan the tests in
     */
    public function __construct($directory)
    {
        $versionCommand = $this->command . " --version";
        $this->versionInfo = `$versionCommand`.".";

        $this->findTests($directory);
    }

    /**
     * Scans a directory for tests
     *
     * @param string $directory The directory to find the tests in
     */
    private function findTests($directory)
    {
        $testFiles = scandir($directory);

        foreach($testFiles as $testFile) {
	        if($testFile[0] != ".") {
                $path = $directory . "/" . $testFile;
                if(is_dir($path)) {
                    $this->findTests($path);
                }
                if(substr($path, -4) == ".php") {
                    $testCommand = $this->command . " --bootstrap include.php $path";
                    $this->testCases[$path] = $testCommand;
          		}
            }
      	}
    }

    /**
     * Return the array of testcases
     *
     * @return array The array with testcases
     */
    public function returnTests()
    {
        return $this->testCases;
    }

    /**
     * Execute the tests
     */
    public function executeTests()
    {
        foreach($this->testCases as $testLocation => &$command) {
            $command = `$command`;
            $command = trim(trim(trim(str_replace($this->versionInfo, "", $command)), "."));
        }
    }
}

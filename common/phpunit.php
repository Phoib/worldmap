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
        $this->versionInfo = trim(`$versionCommand`);

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
     * Return rendered table of the testcases
     *
     * @return \htmlChunk Object with html of the testcases
     */
    public function returnTable()
    {
        $results = $this->returnTests();
        $preRender = array(
            array(
                "TestFile", "Execution Time", "Memory", "Result", "Errors"
            )
        );
        foreach($results as $file => $result) {
            switch($result[0]) {
                case 'I':
                    $result = explode("\n", $result);
                    $stats = explode(",", $result[2]);
                    $preRender[] = array(
                        $file, $stats[0], $stats[1], $result[4], $result[5]
                    );
                    break;
                case 'F':
                    $result = explode("\n", $result);
                    $resultSize = sizeof($result);
                    unset($result[0]);
                    unset($result[1]);
                    $stats = explode(",", $result[2]);
                    unset($result[2]);
                    unset($result[3]);
                    unset($result[5]);
                    $failure = $result[$resultSize -2 ] . " " . $result[$resultSize -1];
                    unset($result[$resultSize -3]);
                    unset($result[$resultSize -2]);
                    unset($result[$resultSize -1]);
                    foreach($result as &$line) {
                        $line = htmlspecialchars($line);
                    }
                    $error = implode("<br>", $result);
                    $preRender[] = array(
                        $file, $stats[0], $stats[1], $failure, $error
                    );
                    break;
                default:
                    $result = explode("\n", $result);
                    $stats = explode(",", $result[0]);
                    $preRender[] = array(
                        $file, $stats[0], $stats[1], $result[2]
                    );
                    break;
            }
        }
        $preRender[] = array($this->versionInfo);
        return htmlChunk::generateTableFromArray($preRender, true);
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

<?php
/**
 * Class making the autotesting of PHP code possible in this project
 *
 * @author Emiel Suilen
 */
class phpUnit
{
    private $command = "/usr/bin/php common/phpunit.phar";

    private $versionInfo = "";

    private $testCases = array();

    public function __construct($directory)
    {
        $versionCommand = $this->command . " --version";
        $this->versionInfo = `$versionCommand`.".";

        $this->findTests($directory);
    }

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

    public function returnTests()
    {
        return $this->testCases;
    }

    public function executeTests()
    {
        foreach($this->testCases as $testLocation => &$command) {
            $command = `$command`;
            $command = trim(trim(trim(str_replace($this->versionInfo, "", $command)), "."));
        }
        var_dump($this->testCases);
    }
}

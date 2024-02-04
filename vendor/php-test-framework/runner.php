<?php

namespace stf {

    use Error;
    use Exception;
    use RuntimeException;

    $collectedTestNames = [];
    $filteredNames = [];

    function runTests(?ResultReporter $reporter = null): void {
        global $filteredNames;

        $opts = getopt('t:', ['testToRun:']);
        if (isset($opts['testToRun'])) {
            $filteredNames[] = $opts['testToRun'];
        }

        $successful = 0;
        foreach (getTestsToRun() as $entry) {
            [$testName, $fn] = $entry;

            try {
                getGlobals()->getBrowser()->reset();

                $fn();

                if (!getGlobals()->leaveBrowserOpen) {
                    getGlobals()->getBrowser()->reset();
                }

                $successful++;

                reportSuccess($testName);

            } catch (FrameworkException $ex) {

                handleFrameworkException($ex, $testName);

                printPageSourceIfNeeded();

            } catch (Error | Exception $e) {
                reportFailure($testName, $e);
            }
        }

        printf("\n%s of %s tests passed.\n", $successful, count(getAllTestNames()));

        if ($reporter && !containsSelectedTests(getTestNamesToRun())) {
            print $reporter->execute($successful);
        }
    }

    function reportFailure($testName, $ex) {
        $details = $ex->getMessage();

        print("##teamcity[testStarted name='$testName']" . PHP_EOL);
        print("##teamcity[testFailed name='$testName' message='' details='$details']" . PHP_EOL);
    }


    function reportSuccess($testName): void {
        printf("%s OK\n", $testName);

        print("##teamcity[testStarted name='$testName']" . PHP_EOL);
        print("##teamcity[testFinished name='$testName' duration='0']" . PHP_EOL);
    }

    function printPageSourceIfNeeded() {
        if (!getGlobals()->printPageSourceOnError) {
            return;
        }

        $response = getGlobals()->getBrowser()->getResponse();

        $text = $response ? $response->getContents() : 'Nothing fetched yet';

        print("##################  Page source start #################### \n");
        print $text . PHP_EOL;
        print("##################  Page source end ###################### \n");
    }

    function handleFrameworkException(FrameworkException $ex, string $testName) {
        [$callerFile, $callerLine] = getCallerLineAndFile($ex, $testName);
        printf("\n### Test %s failed on line %s in file %s(%s)\n\n",
            $testName, $callerLine, $callerFile, $callerLine);
        printf("ERROR %s: %s\n\n", $ex->getCode(), $ex->getMessage());
        if (getGlobals()->printStackTrace) {
            printf("Stack trace: %s\n\n", $ex->getTraceAsString());
        }

        reportFailure($testName, $ex);
    }

    function getCallerLineAndFile(FrameworkException $ex, string $testName) : array {
        $trace = $ex->getTrace();

        for ($i = 0; $i < count($trace); $i++) {
            if ($trace[$i]['function'] === '{closure}') {
                $callerFile = $trace[$i - 1]['file'];
                $callerLine = $trace[$i - 1]['line'];

                return [$callerFile, $callerLine];
            }
        }

        throw new RuntimeException('Unexpected error');
    }

    function getAllTestNames() : array {
        global $collectedTestNames;

        return array_map(function($entry) {
                return $entry[0];
            }, $collectedTestNames);
    }

    function getTestsToRun(): array {
        global $collectedTestNames;

        $namesToRun = getTestNamesToRun();

        return array_filter($collectedTestNames, function($entry) use ($namesToRun) {
            return in_array($entry[0], $namesToRun);
        });

    }

    function getTestNamesToRun(): array {
        global $filteredNames;

        $testNames = getAllTestNames();

        if (containsSelectedTests($testNames)) {
            $testNames = array_filter($testNames, function($name) use ($filteredNames) {
                return startsWith($name, '_') || in_array($name, $filteredNames);
            });
        }

        return $testNames;
    }

    function containsSelectedTests($testNames) : bool {
        global $filteredNames;

        if (count($filteredNames) > 0) {
            return true;
        }

        foreach ($testNames as $name) {
            if (startsWith($name, '_')) {
                return true;
            }
        }
        return false;
    }

    function startsWith($subject, $match) : bool {
        return stripos($subject, $match) === 0;
    }

    function getTestFunctionNames(string $src): array {

        $tokens = token_get_all($src);

        $result = [];
        while (count($tokens)) {
            $token = array_shift($tokens);

            if (is_array($token)
                && token_name($token[0]) === 'T_COMMENT'
                && strpos($token[1], '#Helpers') !== false) {

                return $result;
            }

            if (is_array($token) && token_name($token[0]) === 'T_FUNCTION') {
                $token = array_shift($tokens);
                if (is_array($token) && token_name($token[0]) === 'T_WHITESPACE') {
                    $token = array_shift($tokens);
                }
                if ($token === '(') { // anonymous function
                    continue;
                } else if (is_array($token) && token_name($token[0]) === 'T_STRING') {
                    $result[] = $token[1];
                } else {
                    throw new RuntimeException('Unexpected error');
                }
            }
        }

        return $result;
    }

    function runAllTestsInDirectory($directory, $suiteFile) {
        $files = scandir($directory);

        $testCount = 0;
        $passedCount = 0;
        foreach ($files as $file) {
            if (!is_file($file)) {
                continue;
            } else if (strpos($suiteFile, $file) !== false) {
                continue;
            }

            $cmd = sprintf('php %s', $file);

            $output = [];

            exec($cmd, $output);

            $outputString = implode("\n", $output);

            $allPassed = didAllTestsPass($outputString);

            $result =  $allPassed ? ' OK' : " NOK";

            $testCount++;
            if ($allPassed) {
                $passedCount++;
            }

            printf("%s%s\n", $file, $result);
        }

        printf("\n%s of %s tests passed.\n", $passedCount, $testCount);
    }

    function didAllTestsPass(string $output) : bool {
        preg_match("/(\d+) of (\d+) tests passed./", $output, $matches);

        return count($matches) && $matches[1] == $matches[2];
    }
}

namespace {

    function test($name, $fn): void {
        global $collectedTestNames;

        $collectedTestNames[] = [$name, $fn];
    }

}
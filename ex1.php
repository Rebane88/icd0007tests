<?php

require_once 'vendor/php-test-framework/public-api.php';

function repositoryContainsThreeHtmlFiles() {
    global $argc, $argv;

    if ($argc < 2) {
        die('Pass directory to scan as an argument' . PHP_EOL);
    } else {
        $path = realpath($argv[1]);
    }

    if ($path === false) {
        die('Argument is not a correct directory' . PHP_EOL);
    }

    $it = new RecursiveDirectoryIterator($path);
    $it = new RecursiveIteratorIterator($it);
    $it = new RegexIterator($it, '/\.(\w+)$/i', RegexIterator::GET_MATCH);

    $count = 0;
    foreach($it as $each) {
        $extension = strtolower($each[1]);
        if ($extension === 'html') {
            $count++;
        }
    }

    if ($count < 3) {
        fail(ERROR_C01, "Did not find 3 html files from the repository");
    }
}

stf\runTests(new stf\PointsReporter([1 => 1]));

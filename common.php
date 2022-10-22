<?php

require_once 'vendor/php-test-framework/public-api.php';

function repositoryDoesNotContainNonProjectFiles() {
    global $argc, $argv;

    if ($argc < 2) {
        die('Pass directory to scan as an argument' . PHP_EOL);
    } else {
        $path = realpath($argv[1]);
    }

    if ($path === false) {
        die('Argument is not a correct directory' . PHP_EOL);
    }

    $filter = function ($file) {
        return preg_match('/^\\.\\/ex\\d$/', $file->getPathName());
    };

    chdir($path);

    $it = new RecursiveDirectoryIterator('.');
    $it = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($it, $filter));
    $it = new RegexIterator($it, '/\.(\w+)$/i', RegexIterator::GET_MATCH);

    $htmlCount = 0;
    $phpCount = 0;
    foreach($it as $each) {
        $extension = strtolower($each[1]);
        if ($extension === 'html') {
            $htmlCount++;
        }
        if ($extension === 'php') {
            $phpCount++;
        }
    }

    if ($htmlCount > 10) {
        fail(ERROR_C01, "Repository contains too many ($htmlCount) Html files (max 10)");
    }

    if ($phpCount > 12) {
        fail(ERROR_C01, "Repository contains too many ($phpCount) Php files (max 12)");
    }
}

stf\runTests(new stf\PointsReporter([1 => 1]));

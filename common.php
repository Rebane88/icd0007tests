<?php

require_once 'vendor/php-test-framework/public-api.php';

if ($argc < 2) {
    die('Pass directory to scan as an argument' . PHP_EOL);
} else {
    $path = realpath($argv[1]);
}

if ($path === false) {
    die('Argument is not a correct directory' . PHP_EOL);
}

function _repositoryDoesNotContainNonProjectPhpFiles() {
    global $path;

    $count = getFileCount($path, 'php');

    if ($count > 12) {
        fail(ERROR_C01, "Repository contains too many ($count) Php files (max 12)");
    }
}

function _repositoryDoesNotContainNonProjectHtmlFiles() {
    global $path;

    $count = getFileCount($path, 'html');

    if ($count > 12) {
        fail(ERROR_C01, "Repository contains too many ($count) Html files (max 12)");
    }
}

#Helpers

function getFileCount($path, $extension): int {

    $filter = function ($file) {
        return ! preg_match('/^(\\.\\/ex\\d)|vendor$/', $file->getPathName());
    };

    chdir($path);

    $it = new RecursiveDirectoryIterator('.');
    $it = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($it, $filter));
    $it = new RegexIterator($it, '/\.(\w+)$/i', RegexIterator::GET_MATCH);

    $count = 0;
    foreach($it as $each) {
        if (strtolower($each[1]) === $extension) {
            $count++;
        }
    }

    return $count;
}

stf\runTests(new stf\PointsReporter([1 => 1]));

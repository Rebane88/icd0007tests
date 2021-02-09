<?php

require_once 'common.php';

if ($argc < 2) {
    die('Pass directory to scan as an argument' . PHP_EOL);
} else {
    $path = realpath($argv[1]);
}

if ($path === false) {
    die('Argument is not a correct directory');
}

$it = new RecursiveDirectoryIterator($path);
$it = new RecursiveIteratorIterator($it);
$it = new RegexIterator($it, '/\.(\w+)$/i', RecursiveRegexIterator::GET_MATCH);

$counts = getCounts($it);

if (!isset($counts['css'])
    || !isset($counts['html'])
    || $counts['css'] < 1
    || $counts['html'] < 4) {

    print 'Repository must contain at least four files with html '
        . 'extension and one file with css extension'
        . PHP_EOL;

    die(sprintf(RESULT_PATTERN, 0, MAX_POINTS));

} else {
    printf(RESULT_PATTERN, MAX_POINTS, MAX_POINTS);
}


function getCounts($it) {
    $counts = [];

    foreach($it as $each) {
        $ext = strtolower($each[1]);

        $counts[$ext] = isset($counts[$ext])
            ? $counts[$ext] + 1
            : 1;
    }

    return $counts;
}

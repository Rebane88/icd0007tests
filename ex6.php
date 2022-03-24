<?php

require_once 'vendor/php-test-framework/domain.php';

if ($argc < 4) {
    die('Pass project directory, username and secret as arguments.' . PHP_EOL);
} else {
    $path = realpath($argv[1]);
    $userName = $argv[2];
    $secret = $argv[3];
}

if ($path === false) {
    die('Path is not a correct directory.' . PHP_EOL);
}

$dataFile = "$path/ex6/tokens.txt";

if (!file_exists($dataFile)) {
    die("can't find ex6/tokens.txt from $path" . PHP_EOL);
}

$dict = [];

foreach (file($dataFile) as $line) {
    if (!trim($line)) {
        continue;
    }

    if (preg_match('/Token (\d+): (\w+)/', $line, $matches)) {
        $dict[$matches[1]] = $matches[2];
    }
}

$errors = [];

foreach (range(1, 21) as $nr) {

    $key = strval($nr);
    $actualValue = $dict[$key] ?? '';

    if (empty($actualValue)) {
        $errors[] = ("Token for exercise $nr is missing");
    } else if (!isValid($nr, $userName, $secret, $actualValue)) {
        $errors[] = ("Incorrect token for exercise $nr");
    }
}

if (empty($errors)) {

    printf(RESULT_PATTERN, 1, 1);

} else {
    print join(PHP_EOL, $errors);

    printf(RESULT_PATTERN, 0, 1);
}

function isValid($exNo, $userName, $secret, $actualValue) : bool {
    if ($exNo === 1 || $exNo === 8 || $exNo === 15) {
        $userName = '';
    }

    $hash = sha1($exNo . $userName . $secret);

    $hash = substr($hash, 0, 15);

    return $actualValue === $hash;
}

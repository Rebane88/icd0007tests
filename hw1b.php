<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

if ($argc < 3) {
    die('Pass project directory and Csv fail as arguments.' . PHP_EOL);
} else {
    $path = realpath($argv[1]);
    $namesCsv = realpath($argv[2]);
}

if ($path === false) {
    die('Argument is not a correct directory.' . PHP_EOL);
}

$json = readJsonFileFrom($path);

$fullName = $json['firstName'] . ' ' . $json['lastName'];

if (nameExistsInDeclaredNames($fullName, $namesCsv)) {
    printf(RESULT_PATTERN_SHORT, RESULT_PASSED);
} else {
    print "There is no declaration with the name '$fullName' in Õis (as of 07.02.2025). 
                 If you declared the course later and the name is correct you will get 
                 the points on 23.02.2025";

    printf(RESULT_PATTERN_SHORT, RESULT_FAILED);

    exit(1);
}

function nameExistsInDeclaredNames(string $name, string $csvFile): bool {
    $file = new SplFileObject($csvFile);
    $file->setFlags(SplFileObject::READ_CSV);
    $file->setCsvControl(';');
    foreach ($file as $row) {
        if (!isset($row[2]) || !isset($row[3])) {
            continue;
        }

        if ($name === trim($row[2]) . ' ' . trim($row[3])) {
            return true;
        }
    }

    return false;
}

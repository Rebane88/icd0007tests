<?php

require_once 'vendor/php-test-framework/public-api.php';

const MAX_POINTS = 4;

if ($argc < 3) {
    die('Pass project directory and Csv fail as arguments.' . PHP_EOL);
} else {
    $path = realpath($argv[1]);
    $namesCsv = realpath($argv[2]);
}

if ($path === false) {
    die('Argument is not a correct directory.' . PHP_EOL);
}

$infoFile = "$path/info.json";

if (!file_exists($infoFile)) {
    die("can't find info.json from $path" . PHP_EOL);
}

$string = file_get_contents($infoFile);
$json = json_decode($string, true);

$errors = [];

if (!$json['firstName']) {
    $errors[] = 'First name is missing';
}
if (!$json['lastName']) {
    $errors[] = 'Last name is missing';
}
if (!$json['passwordHash']) {
    $errors[] = 'Password hash is missing';
}
if ($json['iHaveReadTheRulesOfTheCourse'] !== true) {
    $errors[] = 'iHaveReadTheRulesOfTheCourse must be true';
}

$fullName = $json['firstName'] . ' ' . $json['lastName'];

if (!nameExistsInDeclaredNames($fullName, $namesCsv)) {
    $errors['name'] = "There is no declaration with the name '$fullName' in Õis (as of 03.02.2023). 
                 If you declared the course later and the name is correct you will get 
                 the points on 19.02.2023";
}

if (!$errors) {

    printf(RESULT_PATTERN_SHORT, MAX_POINTS);

} else {
    print join(PHP_EOL, $errors);

    $score = count($errors) === 1 && $errors['name'] ? MAX_POINTS / 2 : 0;

    die(sprintf(RESULT_PATTERN_SHORT, $score));
}

function nameExistsInDeclaredNames(string $name, string $csvFile): bool {
    $file = new SplFileObject($csvFile);
    $file->setFlags(SplFileObject::READ_CSV);
    $file->setCsvControl(';');
    foreach ($file as $row) {
        if (isset($row[2]) && $name === $row[2] . ' ' . $row[3]) {
            return true;
        }
    }

    return false;
}

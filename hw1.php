<?php

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
    $errors['name'] = "There is no declaration with name '$fullName' in Õis (as of 28.01.2022). 
                 If you declared the course later and the name is correct you will get 
                 the points on 14.02.2022";
}

if (!$errors) {

    printf(RESULT_PATTERN, MAX_POINTS, MAX_POINTS);

} else {
    print join(PHP_EOL, $errors);

    $score = count($errors) === 1 && $errors['name'] ? 2 : 0;

    die(sprintf(RESULT_PATTERN, $score, MAX_POINTS));
}

function nameExistsInDeclaredNames(string $name, string $csvFile) : bool {
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

<?php

require_once 'common.php';

if ($argc < 2) {
    die('Pass directory to scan as an argument.' . PHP_EOL);
} else {
    $path = realpath($argv[1]);
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
    $errors[] = 'firstName is missing';
}
if (!$json['lastName']) {
    $errors[] = 'lastName is missing';
}
if (!$json['passwordHash']) {
    $errors[] = 'passwordHash is missing';
}
//if ($json['studyProgram'] !== 'D' && $json['studyProgram'] !== 'O') {
//    $errors[] = 'studyProgram must be D or O';
//}
if ($json['iHaveReadTheRulesOfTheCourse'] !== true) {
    $errors[] = 'iHaveReadTheRulesOfTheCourse must be true';
}

if (!$errors) {

    printf(RESULT_PATTERN, MAX_POINTS, MAX_POINTS);

} else {
    print join(PHP_EOL, $errors);

    die(sprintf(RESULT_PATTERN, 0, MAX_POINTS));
}


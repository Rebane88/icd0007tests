<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const PROJECT_DIRECTORY = '';

test('Finds days under target temperature', function () {
    chdir(getProjectDirectory() . '/ex2');

    $command = 'php temps.php --command days-under-temp --year 2023 --temp -5';

    $result = trim(shell_exec($command));

    assertThat($result, is('26.25'));
});

test('Finds days under target temperature dictionary', function () {
    chdir(getProjectDirectory() . '/ex2');

    $command = 'php temps.php --command days-under-temp-dict --temp -5';

    $result = trim(shell_exec($command));

    assertThat($result, is('[2021 => 41.04, 2022 => 20.5, 2023 => 26.25]'));
});

test('Finds average winter temperature', function () {
    chdir(getProjectDirectory() . '/ex2');

    $command = 'php temps.php --command avg-winter-temp --year 2022/2023';

    $result = trim(shell_exec($command));

    assertThat($result, is('-1.62'));
});

test('Shows error on missing parameters', function () {
    chdir(getProjectDirectory() . '/ex2');

    $command = 'php temps.php --command days-under-temp --year 2021';

    $output = '';
    $resultCode = 0;
    exec($command . ' 2>&1', $output, $resultCode);

    assertThat(strlen($output[0]) > 10, is(true));

    assertThat($resultCode, is(1));
});

test('Errors are printed to stderr', function () {

    chdir(getProjectDirectory() . '/ex2');

    $command = 'php temps.php --command days-under-temp --year 2021';

    $output = shell_exec($command);

    assertThat(strlen($output), is(0));
});

stf\runTests(getPassFailReporter(5));

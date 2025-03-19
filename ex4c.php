<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const PROJECT_DIRECTORY = '';

test('Finds minimum temperature', function () {
    chdir(getProjectDirectory() . '/ex4/cli');

    $command = 'php weather.php --command min';

    $output = trim(shell_exec($command));

    assertThat($output, is('6.2'));
});

test('Finds maximum temperature', function () {
    chdir(getProjectDirectory() . '/ex4/cli');

    $command = 'php weather.php --command max';

    $output = trim(shell_exec($command));

    assertThat($output, is('9.8'));
});

test('Adding new temperature returns program state', function () {
    chdir(getProjectDirectory() . '/ex4/cli');

    $command = 'php weather.php --command add --value 5.123';

    $programState = trim(shell_exec($command));

    assertThat($programState, containsString('5.123'));
});

test('Restores state from input parameter', function () {
    chdir(getProjectDirectory() . '/ex4/cli');

    $command = 'php weather.php --command add --value 15.6';

    $programState = trim(shell_exec($command));

    $command = 'php weather.php --command add --value 4.3 --state ' . $programState;

    $programState = trim(shell_exec($command));

    $command = 'php weather.php --command max --state ' . $programState;

    $output = trim(shell_exec($command));

    assertThat($output, is('15.6'));

    $command = 'php weather.php --command min --state ' . $programState;

    $output = trim(shell_exec($command));

    assertThat($output, is('4.3'));
});

stf\runTests(getPassFailReporter(4));

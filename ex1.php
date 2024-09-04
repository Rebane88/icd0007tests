<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const PROJECT_DIRECTORY = '.';

test('Checks whether list contains specified element', function () {
    require_once 'ex1/ex2.php';

    $list = [1, 2, 3, 2, 6];

    assertThat(isInList($list, 7), is(false));

    assertThat(isInList($list, 3), is(true));

    assertThat(isInList($list, '3'), is(false));
});

test('Converts list to string', function () {
    require_once 'ex1/ex3.php';

    $list = [3, 2, 6];

    assertThat(listToString($list), is('[3, 2, 6]'));
});

test('Converts string to integer list', function () {
    require_once 'ex1/ex4.php';

    $input = '[3, 2, 6]';

    assertThat(stringToIntegerList($input), is([3, 2, 6]));
});

test('Gets average weights by type', function () {
    require_once 'ex1/ex5.php';

    $input = [
        ['type' => 'apple', 'weight' => 0.21],
        ['type' => 'orange', 'weight' => 0.18],
        ['type' => 'orange', 'weight' => 0.16],
        ['type' => 'apple', 'weight' => 0.22],
        ['type' => 'orange', 'weight' => 0.15]
    ];

    $result = getAverageWeightsByType($input);

    assertThat($result['apple'], is(0.22));
    assertThat($result['orange'], is(0.16));
});

test('Gets days under target temperature', function () {
    chdir(getProjectDirectory() . '/ex1');

    require_once 'ex7.php';

    assertThat(getDaysUnderTemp(2021, -10), isCloseTo(12.67));
    assertThat(getDaysUnderTemp(2022, -10), isCloseTo(1.96));
    assertThat(getDaysUnderTemp(2023, -10), isCloseTo(4.63));

    assertThat(getDaysUnderTemp(2021, -5), isCloseTo(41.04));
    assertThat(getDaysUnderTemp(2022, -5), isCloseTo(20.5));
    assertThat(getDaysUnderTemp(2023, -5), isCloseTo(26.25));
});

test('Gets days under target temperature dictionary', function () {

    chdir(getProjectDirectory() . '/ex1');

    require_once 'ex8.php';

    $dict = getDaysUnderTempDictionary(-10);

    assertThat($dict[2021], isCloseTo(12.67));
    assertThat($dict[2022], isCloseTo(1.96));
    assertThat($dict[2023], isCloseTo(4.63));

});

test('Converts dictionary to string', function () {

    require_once 'ex1/ex8.php';

    $string = dictToString(['a' => 1, 'b' => 2]);

    assertThat($string, is('[a => 1, b => 2]'));
});

extendIncludePath($argv, PROJECT_DIRECTORY);

stf\runTests(getPassFailReporter(7));

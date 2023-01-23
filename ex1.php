<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common-functions.php';

const PROJECT_DIRECTORY = '';

function checksWhetherListContainsSpecifiedElement() {

    require_once 'ex1/ex2.php';

    $list = [1, 2, 3, 2, 6];

    assertThat(isInList($list, 7), is(false));

    assertThat(isInList($list, 3), is(true));

    assertThat(isInList($list, '3'), is(false));
}

function convertsListToString() {

    require_once 'ex1/ex3.php';

    $list = [3, 2, 6];

    assertThat(listToString($list), is('[3, 2, 6]'));
}

function convertsStringToIntegerList() {

    require_once 'ex1/ex4.php';

    $input = '[3, 2, 6]';

    assertThat(stringToIntegerList($input), is([3, 2, 6]));
}

function savesAndReadsPosts() {

    require_once 'ex1/ex5.php';

    $title = getRandomString(5);
    $text = getRandomString(10);

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

function savesPostsContainingDifferentSymbols() {

    require_once 'ex1/ex5.php';

    $title = getRandomString(5);
    $text = getRandomString(10) . ".'\n;";

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

function findsDaysUnderTargetTemperature() {

    chdir(getProjectDirectory() . '/ex1');

    require_once 'ex7.php';

    assertThat(getDaysUnderTemp(2019, -10), isCloseTo(3.88));
    assertThat(getDaysUnderTemp(2020, -10), isCloseTo(0.21));
    assertThat(getDaysUnderTemp(2021, -10), isCloseTo(13.92));

    assertThat(getDaysUnderTemp(2019, -5), isCloseTo(15.63));
    assertThat(getDaysUnderTemp(2020, -5), isCloseTo(4.96));
    assertThat(getDaysUnderTemp(2021, -5), isCloseTo(42.92));
}

function findsDaysUnderTargetTemperatureDictionary() {

    chdir(getProjectDirectory() . '/ex1');

    require_once 'ex8.php';

    $dict = getDaysUnderTempDictionary(-10);

    assertThat($dict[2019], isCloseTo(3.88));
    assertThat($dict[2020], isCloseTo(0.21));
    assertThat($dict[2021], isCloseTo(13.92));

}

function convertsDictionaryToString() {

    require_once 'ex1/ex8.php';

    $string = dictToString(['a' => 1, 'b' => 2]);

    assertThat($string, is('[a => 1, b => 2]'));
}

extendIncludePath($argv, PROJECT_DIRECTORY);

stf\runTests(new stf\PassFailReporter(7));

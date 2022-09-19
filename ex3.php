<?php

require_once 'vendor/php-test-framework/public-api.php';

const PROJECT_DIRECTORY = '';

function checksWhetherListContainsSpecifiedElement() {

    require_once 'ex3/ex2.php';

    $list = [1, 2, 3, 2, 6];

    assertThat(isInList($list, 7), is(false));

    assertThat(isInList($list, 3), is(true));
}

function filtersOutOddNumbers() {

    require_once 'ex3/ex3.php';

    $list = [1, 2, 3, 2, 5, 8];

    assertThat(getOddNumbers($list), is([1, 3, 5]));
}

function canSavePosts() {

    require_once 'ex3/ex6.php';

    $title = getRandomString(5);
    $text = getRandomString(10);

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

function canSavePostsContainingDifferentSymbols() {

    require_once 'ex3/ex6.php';

    $title = getRandomString(5);
    $text = getRandomString(10) . ".'\n;";

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

#Helpers

extendIncludePath($argv, PROJECT_DIRECTORY);

stf\runTests(new stf\PointsReporter([4 => 1]));

<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080/ex2/nav/';

setBaseUrl(BASE_URL);

function indexToA() {
    navigateTo(BASE_URL . '');

    clickLinkWithText("a.html");

    assertCurrentUrl(BASE_URL . "a/a.html");
}

function aToE() {
    navigateTo(BASE_URL . 'a/a.html');

    clickLinkWithText("e.html");

    assertCurrentUrl(BASE_URL . "a/b/c/d/e/e.html");
}

function eToD() {
    navigateTo(BASE_URL . 'a/b/c/d/e/e.html');

    clickLinkWithText("d.html");

    assertCurrentUrl(BASE_URL . "a/b/c/d/d.html");
}

function dToB() {
    navigateTo(BASE_URL . 'a/b/c/d/d.html');

    clickLinkWithText("b.html");

    assertCurrentUrl(BASE_URL . "a/b/b.html");
}

function emptyLink() {
    navigateTo(BASE_URL . 'a/b/c/d/e/f/f.html');

    $linkText = "shortest self";

    $href = getHrefFromLinkWithText($linkText);

    clickLinkWithText($linkText);

    assertCurrentUrl(BASE_URL . "a/b/c/d/e/f/f.html");

    assertThat(strlen($href), is(0),
        sprintf("'%s' is not the shortest link possible", $href));
}

function directoryLink() {
    navigateTo(BASE_URL . 'a/b/c/d/e/f/f.html');

    $linkText = "shortest f/index.html";

    $href = getHrefFromLinkWithText($linkText);

    clickLinkWithText($linkText);

    assertCurrentUrl(BASE_URL . "a/b/c/d/e/f/");

    assertThat(strlen($href), is(1),
        sprintf("'%s' is not the shortest link possible", $href));
}

function cssIsCorrect() {
    $url = "http://localhost:8080/ex2/css/css.html";
    $cmd = sprintf(
        'google-chrome --headless --disable-gpu --no-sandbox --dump-dom %s', $url);

    exec($cmd, $output, $exitCode);

    if ($exitCode !== 0) {
        printf("error on running chrome\n");
        return;
    }

    $source = implode("\n", $output);

    if (strpos($source, '5 of 5 correct') === false) {
        fail(ERROR_C01, "Css is not correct");
    }
}

stf\runTests(new stf\PointsReporter([6 => 1]));

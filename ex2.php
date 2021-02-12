<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

setBaseUrl(BASE_URL);

function indexToA() {
    navigateTo('/nav/');

    clickLinkByText("a.html");

    assertCurrentUrl(BASE_URL . "/nav/a/a.html");
}

function aToE() {
    navigateTo('/nav/a/a.html');

    clickLinkByText("e.html");

    assertCurrentUrl(BASE_URL . "/nav/a/b/c/d/e/e.html");
}

function eToD() {
    navigateTo('/nav/a/b/c/d/e/e.html');

    clickLinkByText("d.html");

    assertCurrentUrl(BASE_URL . "/nav/a/b/c/d/d.html");
}

function dToB() {
    navigateTo('/nav/a/b/c/d/d.html');

    clickLinkByText("b.html");

    assertCurrentUrl(BASE_URL . "/nav/a/b/b.html");
}

function emptyLink() {
    navigateTo('/nav/a/b/c/d/e/f/f.html');

    $linkText = "shortest self";

    $href = getLinkHrefByText($linkText);

    clickLinkByText($linkText);

    assertCurrentUrl(BASE_URL . "/nav/a/b/c/d/e/f/f.html");

    assertThat(strlen($href), is(0),
        sprintf("%s is not the shortest link possible", $href));
}

function directoryLink() {
    navigateTo('/nav/a/b/c/d/e/f/f.html');

    $linkText = "shortest f/index.html";

    $href = getLinkHrefByText($linkText);

    clickLinkByText($linkText);

    assertCurrentUrl(BASE_URL . "/nav/a/b/c/d/e/f/");

    assertThat(strlen($href), is(1),
        sprintf("%s is not the shortest link possible", $href));
}

function cssIsCorrect() {
    $url = sprintf("%s/css/css.html", BASE_URL);
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

stf\runTests(new stf\PointsReporter([6 => 5]));

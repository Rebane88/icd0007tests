<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common-functions.php';

const BASE_URL = 'http://localhost:8080/';

const PROJECT_DIRECTORY = '';

function indexToA() {
    navigateTo(getUrl());

    clickLinkWithText('a.html');

    assertCurrentUrl(getUrl('a/a.html'));
}

function aToE() {
    navigateTo(getUrl('a/a.html'));

    clickLinkWithText('e.html');

    assertCurrentUrl(getUrl('a/b/c/d/e/e.html'));
}

function eToD() {
    navigateTo(getUrl('a/b/c/d/e/e.html'));

    clickLinkWithText('d.html');

    assertCurrentUrl(getUrl('a/b/c/d/d.html'));
}

function dToB() {
    navigateTo(getUrl('a/b/c/d/d.html'));

    clickLinkWithText('b.html');

    assertCurrentUrl(getUrl('a/b/b.html'));
}

function emptyLink() {
    navigateTo(getUrl('a/b/c/d/e/f/f.html'));

    $linkText = 'shortest self';

    $href = getHrefFromLinkWithText($linkText);

    clickLinkWithText($linkText);

    assertCurrentUrl(getUrl('a/b/c/d/e/f/f.html'));

    assertThat(strlen($href), is(0),
        "'$href' is not the shortest link possible");
}

function directoryLink() {
    navigateTo(getUrl('a/b/c/d/e/f/f.html'));

    $linkText = 'shortest f/index.html';

    $href = getHrefFromLinkWithText($linkText);

    clickLinkWithText($linkText);

    assertCurrentUrl(getUrl('a/b/c/d/e/f/'));

    assertThat(strlen($href), is(1),
        "'$href' is not the shortest link possible");
}

#Helpers

function getUrl(string $relativeUrl = ''): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/ex2/nav/$relativeUrl";
}

setBaseUrl(BASE_URL);

extendIncludePath($argv, PROJECT_DIRECTORY);

stf\runTests(getPassFailReporter(6));

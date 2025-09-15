<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

test('Landing page has menu with correct links', function () {
    navigateTo(getUrl('temps.php'));

    assertPageContainsLinkWithId('days-under-temp');
    assertPageContainsLinkWithId('avg-winter-temp');
});

test('Calculate days under temperature for selected year', function () {
    navigateTo(getUrl('temps.php'));

    selectOptionWithValue('year', '2023');

    setTextFieldValue('temp', '-5');

    clickButton('command');

    assertPageContainsText('26.25');
});

test('Calculate average winter temperature for selected year', function () {
    navigateTo(getUrl('temps.php'));

    clickLinkWithId('avg-winter-temp');

    selectOptionWithValue('year', '2021/2022');

    clickButton('command');

    assertPageContainsText('-2.12');
});

function getUrl(string $relativeUrl): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/ex3/$relativeUrl";
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(3));

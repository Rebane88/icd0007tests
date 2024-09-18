<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

test('Landing page has menu with correct links', function () {
    navigateTo(getUrl());

    assertPageContainsLinkWithId('c2f');
    assertPageContainsLinkWithId('f2c');
});

test('f2c page has menu with correct links', function () {
    navigateTo(getUrl());

    clickLinkWithId('f2c');

    assertPageContainsLinkWithId('c2f');
    assertPageContainsLinkWithId('f2c');
});

test('Calculates Celsius to Fahrenheit', function () {
    navigateTo(getUrl());

    setTextFieldValue('temperature', '20');

    clickButton('calculateButton');

    assertPageContainsText('is 68 degrees');
});

test('Calculates Fahrenheit to Celsius', function () {
    navigateTo(getUrl());

    clickLinkWithId('f2c');

    setTextFieldValue('temperature', '68');

    clickButton('calculateButton');

    assertPageContainsText('is 20 degrees');
});

function getUrl(): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/ex3/calc/";
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(4));

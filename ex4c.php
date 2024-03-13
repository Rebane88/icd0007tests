<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

test('Show confirmation page after the form submission', function () {
    navigateTo(getUrl('/ex4/confirm/'));

    setTextFieldValue('data', "hello");

    clickButton('sendButton');

    assertThat(getPageId(), is('confirm-page'));
});

test('Can cancel the operation', function () {
    navigateTo(getUrl('/ex4/confirm/'));

    setTextFieldValue('data', "hello");

    clickButton('sendButton');

    clickLinkWithId('cancel');

    assertThat(getPageId(), is('form-page'));
});

test('Save redirects to the form and shows a message', function () {
    navigateTo(getUrl('/ex4/confirm/'));

    setTextFieldValue('data', "hello");

    clickButton('sendButton');

    clickLinkWithId('confirm');

    assertThat(getPageId(), is('form-page'));

    assertPageContainsElementWithId('message-success');
});

function getUrl(string $relativeUrl): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/$relativeUrl";
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(3));

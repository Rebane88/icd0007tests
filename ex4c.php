<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

function showConfirmationPageAfterFormSubmission() {
    navigateTo(getUrl('/ex4/confirm/'));

    setTextFieldValue('data', "hello");

    clickButton('sendButton');

    assertThat(getPageId(), is('confirm-page'));
}

function canCancelOperation() {
    navigateTo(getUrl('/ex4/confirm/'));

    setTextFieldValue('data', "hello");

    clickButton('sendButton');

    clickLinkWithId('cancel');

    assertThat(getPageId(), is('form-page'));
}

function saveRedirectsToFormAndShowsMessage() {
    navigateTo(getUrl('/ex4/confirm/'));

    setTextFieldValue('data', "hello");

    clickButton('sendButton');

    clickLinkWithId('confirm');

    assertThat(getPageId(), is('form-page'));

    assertPageContainsElementWithId('message-success');
}

#Helpers

function getUrl(string $relativeUrl = ''): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/$relativeUrl";
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(3));

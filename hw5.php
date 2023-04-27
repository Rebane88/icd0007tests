<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

function applicationLinksShouldBeInCorrectFormat() {

    gotoLandingPage();

    // expected format is ?key1=value1&key2=value2&...

    assertFrontControllerLink('book-list-link');
    assertFrontControllerLink('book-form-link');
    assertFrontControllerLink('author-list-link');
    assertFrontControllerLink('author-form-link');

    clickBookFormLink();

    assertFrontControllerLink('book-list-link');
    assertFrontControllerLink('book-form-link');
    assertFrontControllerLink('author-list-link');
    assertFrontControllerLink('author-form-link');

    clickAuthorFormLink();

    assertFrontControllerLink('book-list-link');
    assertFrontControllerLink('book-form-link');
    assertFrontControllerLink('author-list-link');
    assertFrontControllerLink('author-form-link');
}

function makesRedirectAfterFormSubmission() {
    gotoLandingPage();

    clickBookFormLink();

    setTextFieldValue('title', "Some Book Title");

    disableAutomaticRedirects();

    clickButton('submitButton');

    assertThat(getResponseCode(), isAnyOf(301, 302, 303));

    assertNoOutput();
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(2));

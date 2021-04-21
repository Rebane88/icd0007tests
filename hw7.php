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

function canUpdateBooksWithSingleAuthor() {

    $originalAuthorName = insertSampleAuthor();
    $newAuthorName = insertSampleAuthor();

    gotoLandingPage();

    clickBookFormLink();

    $bookTitle = getSampleBook()->title;

    setTextFieldValue('title', $bookTitle);
    selectOptionWithText('author1', $originalAuthorName);

    clickBookFormSubmitButton();

    clickLinkWithText($bookTitle);

    assertThat(getFieldValue('title'), is($bookTitle));
    assertThat(getSelectedOptionText('author1'), is($originalAuthorName));

    selectOptionWithText('author1', $newAuthorName);

    clickBookFormSubmitButton();

    assertThat(getPageText(), containsStringOnce($newAuthorName));
    assertThat(getPageText(), doesNotContainString($originalAuthorName));
}

#Helpers

function insertSampleAuthor() : string {

    gotoLandingPage();

    clickAuthorFormLink();

    $author = getSampleAuthor();

    setTextFieldValue('firstName', $author->firstName);
    setTextFieldValue('lastName', $author->lastName);

    clickAuthorFormSubmitButton();

    return $author->firstName . ' ' . $author->lastName;
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintStackTrace(false);
setPrintPageSourceOnError(false);

stf\runTests(new stf\PointsReporter([3 => 5]));

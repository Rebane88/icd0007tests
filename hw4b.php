<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

function canSaveBooksWithMultipleAuthors() {

    $authorName1 = insertSampleAuthor();
    $authorName2 = insertSampleAuthor();

    gotoLandingPage();

    clickBookFormLink();

    $bookTitle = getSampleBook()->title;

    setTextFieldValue('title', $bookTitle);
    selectOptionWithText('author1', $authorName1);
    selectOptionWithText('author2', $authorName2);

    clickBookFormSubmitButton();

    assertThat(getPageText(), containsStringOnce($bookTitle));
    assertThat(getPageText(), containsStringOnce($authorName1));
    assertThat(getPageText(), containsStringOnce($authorName2));
}

function canUpdateBooksWithMultipleAuthors() {

    $authorName1 = insertSampleAuthor();
    $authorName2 = insertSampleAuthor();
    $authorName3 = insertSampleAuthor();

    gotoLandingPage();

    clickBookFormLink();

    $bookTitle = getSampleBook()->title;

    setTextFieldValue('title', $bookTitle);
    selectOptionWithText('author1', $authorName1);
    selectOptionWithText('author2', $authorName2);

    clickBookFormSubmitButton();

    clickLinkWithText($bookTitle);

    assertThat(getFieldValue('title'), is($bookTitle));
    assertThat(getSelectedOptionText('author1'), is($authorName1));
    assertThat(getSelectedOptionText('author2'), is($authorName2));

    selectOptionWithText('author1', $authorName2);
    selectOptionWithText('author2', $authorName3);

    clickBookFormSubmitButton();

    assertThat(getPageText(), containsStringOnce($authorName2));
    assertThat(getPageText(), containsStringOnce($authorName3));
    assertThat(getPageText(), doesNotContainString($authorName1));
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintStackTrace(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(2));

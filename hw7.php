<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080/';

function baseUrlResponds() {
    navigateTo(BASE_URL);
}

function startPageHasMenuWithCorrectLinks() {
    navigateTo(BASE_URL);

    assertPageContainsLinkWithId('book-list-link');
    assertPageContainsLinkWithId('book-form-link');
    assertPageContainsLinkWithId('author-list-link');
    assertPageContainsLinkWithId('author-form-link');
}

function canSaveBooks() {

    navigateTo(BASE_URL);

    clickLinkWithId('book-form-link');

    $book = getSampleBook();

    setTextFieldValue('title', $book->title);

    clickButton('submitButton');

    waitPageText(fn() => containsStringOnce($book->title));

    // check that data is not generated on server side

    useWebDriver(false);

    navigateTo(BASE_URL);

    assertThat(getPageText(), doesNotContainString($book->title));

    useWebDriver(true);
}

function canUpdateBooks() {

    navigateTo(BASE_URL);

    clickLinkWithId('book-form-link');

    $title = getSampleBook()->title;
    $newTitle = getSampleBook()->title;

    setTextFieldValue('title', $title);

    clickButton('submitButton');

    clickLinkWithText($title);

    setTextFieldValue('title', $newTitle);

    clickButton('submitButton');

    waitPageText(fn() => containsStringOnce($newTitle));

    assertThat(getPageText(), doesNotContainString($title));
}

function canDeleteInsertedBooks() {

    navigateTo(BASE_URL);

    clickLinkWithId('book-form-link');

    $book = getSampleBook();

    setTextFieldValue('title', $book->title);

    clickButton('submitButton');

    clickLinkWithText($book->title);

    clickButton('deleteButton');

    assertThat(getPageText(), doesNotContainString($book->title));
}

setBaseUrl(BASE_URL);
useWebDriver(true);
setPrintPageSourceOnError(false);
setLeaveBrowserOpen(false);
setShowBrowser(false);

stf\runTests(new stf\PointsReporter([5 => 4]));
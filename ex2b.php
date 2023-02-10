<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common-functions.php';

const BASE_URL = 'http://localhost:8080';

function containsIndex() {
    navigateTo(getUrl('index.html'));

    if (getResponseCode() !== 200) {
        fail(ERROR_C01, 'Did not find file ' . getUrl('index.html'));
    }
}

function defaultPageIsBookList() {
    navigateTo(getUrl('index.html'));

    assertThat(getPageId(), is('book-list-page'));
}

function bookListPageContainsCorrectMenu() {
    navigateTo(getUrl('index.html'));

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

function bookFormPageContainsCorrectMenu() {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('book-form-link');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

function authorListPageContainsCorrectMenu() {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('author-list-link');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

function authorFormPageContainsCorrectMenu() {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('author-form-link');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

#Helpers

function getUrl(string $relativeUrl = ''): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/ex2/proto/$relativeUrl";
}

setBaseUrl(BASE_URL);

stf\runTests(getPassFailReporter(6));
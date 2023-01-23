<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

function containsIndex() {
    navigateTo('/ex2/proto/index.html');

    if (getResponseCode() !== 200) {
        fail(ERROR_C01, 'Did not find file ex2/proto/index.html');
    }
}

function defaultPageIsBookList() {
    navigateTo('/ex2/proto/index.html');

    assertThat(getPageId(), is('book-list-page'));
}

function bookListPageContainsCorrectMenu() {
    navigateTo('/ex2/proto/index.html');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

function bookFormPageContainsCorrectMenu() {
    navigateTo('/ex2/proto/index.html');

    clickLinkWithId('book-form-link');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

function authorListPageContainsCorrectMenu() {
    navigateTo('/ex2/proto/index.html');

    clickLinkWithId('author-list-link');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

function authorFormPageContainsCorrectMenu() {
    navigateTo('/ex2/proto/index.html');

    clickLinkWithId('author-form-link');

    assertPageContainsRelativeLinkWithId('book-list-link');
    assertPageContainsRelativeLinkWithId('book-form-link');
    assertPageContainsRelativeLinkWithId('author-list-link');
    assertPageContainsRelativeLinkWithId('author-form-link');
}

setBaseUrl(BASE_URL);

stf\runTests(new stf\PassFailReporter(6));
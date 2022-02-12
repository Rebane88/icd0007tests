<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

setBaseUrl(BASE_URL);

function containsIndex() {
    navigateTo('/index.html');

    if (getResponseCode() !== 200) {
        fail(ERROR_C01, "Did not find file named index.html from root directory");
    }
}

function defaultPageIsBookList() {
    navigateTo('/index.html');

    assertThat(getPageId(), is('book-list-page'));
}

function bookListPageContainsCorrectMenu() {
    navigateTo('/index.html');

    assertPageContainsLinkWithId('book-list-link');
    assertPageContainsLinkWithId('book-form-link');
    assertPageContainsLinkWithId('author-list-link');
    assertPageContainsLinkWithId('author-form-link');
}

function bookFormPageContainsCorrectMenu() {
    navigateTo('/index.html');

    clickLinkWithId('book-form-link');

    assertPageContainsLinkWithId('book-list-link');
    assertPageContainsLinkWithId('book-form-link');
    assertPageContainsLinkWithId('author-list-link');
    assertPageContainsLinkWithId('author-form-link');
}

function authorListPageContainsCorrectMenu() {
    navigateTo('/index.html');

    clickLinkWithId('author-list-link');

    assertPageContainsLinkWithId('book-list-link');
    assertPageContainsLinkWithId('book-form-link');
    assertPageContainsLinkWithId('author-list-link');
    assertPageContainsLinkWithId('author-form-link');
}

function authorFormPageContainsCorrectMenu() {
    navigateTo('/index.html');

    clickLinkWithId('author-form-link');

    assertPageContainsLinkWithId('book-list-link');
    assertPageContainsLinkWithId('book-form-link');
    assertPageContainsLinkWithId('author-list-link');
    assertPageContainsLinkWithId('author-form-link');
}

stf\runTests(new stf\PointsReporter([6 => 4]));

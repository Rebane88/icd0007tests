<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

test('Contains index', function () {
    navigateTo(getUrl('index.html'));

    if (getResponseCode() !== 200) {
        fail(ERROR_C01, 'Did not find file ' . getUrl('index.html'));
    }
});

test('Default page is dashboard', function () {
    navigateTo(getUrl('index.html'));

    assertThat(getPageId(), is('dashboard-page'));
});

test('Dashboard page contains correct menu', function () {
    navigateTo(getUrl('index.html'));

    assertContainsCorrectMenu();
});

test('Employee list page contains correct menu', function () {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('employee-list-link');

    assertContainsCorrectMenu();
});

test('Employee form page contains correct menu', function () {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('employee-form-link');

    assertContainsCorrectMenu();
});

test('Task list page contains correct menu', function () {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('task-list-link');

    assertContainsCorrectMenu();
});

test('Task form page contains correct menu', function () {
    navigateTo(getUrl('index.html'));

    clickLinkWithId('task-form-link');

    assertContainsCorrectMenu();
});

function assertContainsCorrectMenu(): void {
    assertPageContainsRelativeLinkWithId('dashboard-link');
    assertPageContainsRelativeLinkWithId('employee-list-link');
    assertPageContainsRelativeLinkWithId('employee-form-link');
    assertPageContainsRelativeLinkWithId('task-list-link');
    assertPageContainsRelativeLinkWithId('task-form-link');
}

function getUrl(string $relativeUrl = ''): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/ex2/proto/$relativeUrl";
}

setBaseUrl(BASE_URL);

stf\runTests(getPassFailReporter(7));
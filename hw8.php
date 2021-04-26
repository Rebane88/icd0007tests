<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080/';

function displaysLoginFormIfNotLoggedIn() {

    navigateTo(BASE_URL);

    assertCorrectPageId('login-form-page');

    assertPageContainsFieldWithName('username');
    assertPageContainsFieldWithName('password');
    assertPageContainsButtonWithName('loginButton');

    assertPageContainsLinkWithId('lang-et-link');
    assertPageContainsLinkWithId('lang-en-link');

    assertPageDoesNotContainElementWithId('book-list-link');
    assertPageDoesNotContainElementWithId('book-form-link');
    assertPageDoesNotContainElementWithId('author-list-link');
    assertPageDoesNotContainElementWithId('author-form-link');
    assertPageDoesNotContainElementWithId('log-out-link');
}

function displaysErrorMessageWhenLoggingInWithBadCredentials() {

    navigateTo(BASE_URL);

    setTextFieldValue('username', 'alice');
    setTextFieldValue('password', 's3cret');

    clickButton('loginButton');

    assertPageContainsElementWithId('error-block');

    assertThat(getFieldValue('username'), is('alice'));
    assertThat(getFieldValue('password'), is('s3cret'));
}

function displaysBookListPageAfterCorrectLogin() {
    login();

    assertCorrectPageId('book-list-page');

    assertPageContainsLinkWithId('book-list-link');
    assertPageContainsLinkWithId('book-form-link');
    assertPageContainsLinkWithId('author-list-link');
    assertPageContainsLinkWithId('author-form-link');
    assertPageContainsLinkWithId('log-out-link');
    assertPageContainsLinkWithId('lang-et-link');
    assertPageContainsLinkWithId('lang-en-link');
}

function userIsLoggedOutWhenLogOutLinkIsClicked() {
    login();

    clickLinkWithId('log-out-link');

    assertPageContainsTextFieldWithName('username');
    assertPageDoesNotContainElementWithId('log-out-link');
    assertPageDoesNotContainElementWithId('book-list-link');
}

function userIsLoggedOutWhenCookieExpires() {
    login();

    deleteSessionCookie();

    navigateTo(BASE_URL);

    assertPageContainsTextFieldWithName('username');
}

function canSwitchLanguageOnLoginForm() {
    navigateTo(BASE_URL);

    $loginButtonLabelEst = getButtonLabel('loginButton');

    clickLinkWithId('lang-en-link');

    $loginButtonLabelEng = getButtonLabel('loginButton');

    assertThat($loginButtonLabelEst !== $loginButtonLabelEng, is(true));
}

function canSwitchLanguageOnListPage() {
    login();

    $listLinkBefore = getTextFromLinkWithId('book-list-link');
    $formLinkBefore = getTextFromLinkWithId('book-form-link');

    clickLinkWithId('lang-en-link');

    $listLinkAfter = getTextFromLinkWithId('book-list-link');
    $formLinkAfter = getTextFromLinkWithId('book-form-link');

    assertThat($listLinkBefore !== $listLinkAfter, is(true));
    assertThat($formLinkBefore !== $formLinkAfter, is(true));
}

function languageIsNotChangedWhenSessionExpires() {
    navigateTo(BASE_URL);

    clickLinkWithId('lang-en-link');

    $labelBefore = getButtonLabel('loginButton');

    deleteSessionCookie();

    navigateTo(BASE_URL);

    $labelAfter = getButtonLabel('loginButton');

    assertThat($labelBefore === $labelAfter, is(true));
}

#Helpers

function login() {
    navigateTo(BASE_URL);

    setTextFieldValue('username', 'user');
    setTextFieldValue('password', 'secret');

    clickButton('loginButton');

}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintStackTrace(false);
setPrintPageSourceOnError(false);

stf\runTests(new stf\PointsReporter([7 => 4, 8 => 5]));

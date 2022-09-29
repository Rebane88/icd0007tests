<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

function canSendMultilineTextToDifferentReceiver() {
    navigateTo(BASE_URL . '/flow/sender.html');

    setTextFieldValue('text', "hello\nworld");

    clickButton('sendButton');

    assertPageContainsText("hello\nworld");
}

function canSendSimpleTextThroughRedirect() {
    navigateTo(BASE_URL . '/flow/sender.php');

    setTextFieldValue('text', 'hello');

    clickButton('sendButton');

    assertPageContainsText('Data was: hello');
}

function canSendMultilineTextThroughRedirect() {
    navigateTo(BASE_URL . '/flow/sender.php');

    setTextFieldValue('text', "hello\nworld");

    clickButton('sendButton');

    assertPageContainsText("Data was: hello\nworld");
}

function landingPageHasMenuWithCorrectLinks() {
    navigateTo(BASE_URL . '/calc/');

    assertPageContainsLinkWithId('c2f');
    assertPageContainsLinkWithId('f2c');
}

function f2cPageHasMenuWithCorrectLinks() {
    navigateTo(BASE_URL . '/calc/');

    clickLinkWithId('f2c');

    assertPageContainsLinkWithId('c2f');
    assertPageContainsLinkWithId('f2c');
}

function calculatesCelsiusToFahrenheit() {
    navigateTo(BASE_URL . '/calc/');

    setTextFieldValue('temperature', '20');

    clickButton('calculateButton');

    assertPageContainsText('is 68 degrees');
}

function calculatesFahrenheitToCelsius() {
    navigateTo(BASE_URL . '/calc/');

    clickLinkWithId('f2c');

    setTextFieldValue('temperature', '68');

    clickButton('calculateButton');

    assertPageContainsText('is 20 degrees');
}

#Helpers

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintStackTrace(false);
setPrintPageSourceOnError(false);

stf\runTests(new stf\PointsReporter([7 => 1]));

<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

test('Checks correct radio', function () {
    navigateTo(getUrl('radios.php'));

    assertThat(getFieldValue('grade'), is('3'));

    navigateTo(getUrl('radios.php?grade=4'));

    assertThat(getFieldValue('grade'), is('4'));
});

test('Checks correct radio with include', function () {
    navigateTo(getUrl('include/'));

    assertThat(getFieldValue('grade'), is('3'));

    navigateTo(getUrl('include/?grade=2'));

    assertThat(getFieldValue('grade'), is('2'));
});

test('Confirmation works with simple text', function () {
    navigateTo(getUrl('confirm/'));

    setTextFieldValue('text', 'hello');

    clickButton('sendButton');

    clickLinkWithText('Confirm');

    assertPageContainsText('Confirmed: hello');
});

test('Confirmation works with different symbols', function () {
    navigateTo(getUrl('confirm/'));

    $text = "hello'\"\n";

    setTextFieldValue('text', $text);

    clickButton('sendButton');

    clickLinkWithText('Confirm');

    assertPageContainsText('Confirmed: ' . $text);
});

test('Calculates arithmetic expressions', function () {
    navigateTo(getUrl('calc/'));

    setTextFieldValue('number', '4');

    clickButton('cmd', 'insert');

    clickButton('cmd', 'plus');

    setTextFieldValue('number', '3');

    clickButton('cmd', 'insert');

    clickButton('cmd', 'evaluate');

    clickButton('cmd', 'minus');

    setTextFieldValue('number', '-2');

    clickButton('cmd', 'insert');

    clickButton('cmd', 'evaluate');

    assertThat(getFieldValue('display'), is('9'));
});

function getUrl(string $relativeUrl): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/ex5/" . $relativeUrl;
}

setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(5));

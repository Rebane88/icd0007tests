<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const PROJECT_DIRECTORY = '';
const BASE_URL = 'http://localhost:8080';

test('Order line repository returns OrderLine objects', function () {
    chdir(getProjectDirectory() . '/ex8/repository');

    require_once 'OrderLineRepository.php';
    require_once 'OrderLine.php';

    $dataFilePath = getProjectDirectory() . '/ex8/repository/orders.txt';

    $dao = new OrderLineRepository($dataFilePath);

    $orderLines = $dao->getOrderLines();

    assertThat($orderLines[0]->productName, is("Pen"));
    assertThat(intval($orderLines[1]->price), is(3));
    assertThat($orderLines[2]->inStock, is(true));
});

test('Converts from Celsius to Fahrenheit', function () {
    navigateTo(BASE_URL . '/ex8/calc/');

    setTextFieldValue('temperature', '20');

    clickButton('cmd');

    assertPageContainsElementWithId('message-block');
    assertPageContainsText('20');
    assertPageContainsText('68');
});

test('Converts from Fahrenheit to Celsius', function () {
    navigateTo(BASE_URL . '/ex8/calc/');

    clickLinkWithId('link-ftc');

    setTextFieldValue('temperature', '86');

    clickButton('cmd');

    assertPageContainsElementWithId('message-block');
    assertPageContainsText('86');
    assertPageContainsText('30');
});

test('Calculator displays error when temperature is not a number', function () {
    navigateTo(BASE_URL . '/ex8/calc/');

    setTextFieldValue('temperature', 'abc');
    clickButton('cmd');

    assertPageContainsElementWithId('error-block');
    assertPageContainsText('Temperature must be a number');
    assertThat(getFieldValue('temperature'), is('abc'));

    setTextFieldValue('temperature', '40');
    clickButton('cmd');

    assertPageContainsElementWithId('message-block');
    assertPageContainsText('104');
});

test('Php and Html code is separated', function () {
    $path = getProjectDirectory() . '/ex8/calc';

    assertPhpHtmlSeparated($path);
});

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(5));

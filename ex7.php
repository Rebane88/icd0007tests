<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const PROJECT_DIRECTORY = '';
const BASE_URL = 'http://localhost:8080';

test('Order line repository returns OrderLine objects', function () {
    chdir(getProjectDirectory() . '/ex7');

    require_once 'OrderLineRepository.php';
    require_once 'OrderLine.php';

    $dataFilePath = getProjectDirectory() . '/ex7/data/order.txt';

    $dao = new OrderLineRepository($dataFilePath);

    $orderLines = $dao->getOrderLines();

    assertThat($orderLines[0]->productName, is("Pen"));
    assertThat(intval($orderLines[1]->price), is(3));
    assertThat($orderLines[2]->inStock, is(true));
});

test('Converts from Celsius to Fahrenheit', function () {
    navigateTo(BASE_URL . '/ex7/ex3.php');

    setTextFieldValue('temperature', '20');

    clickButton('calculateButton');

    assertPageContainsText('20');
    assertPageContainsText('68');
});

test('Converts from Fahrenheit to Celsius', function () {
    navigateTo(BASE_URL . '/ex7/ex3.php');

    clickLinkWithId('link-ftc');

    setTextFieldValue('temperature', '86');

    clickButton('calculateButton');

    assertPageContainsText('86');
    assertPageContainsText('30');
});

test('Calculator displays error when input is not a number', function () {
    navigateTo(BASE_URL . '/ex7/ex3.php');

    setTextFieldValue('temperature', 'abc');

    clickButton('calculateButton');

    assertPageContainsText('Input must be a number');

    assertThat(getFieldValue('temperature'), is('abc'));
});

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(4));

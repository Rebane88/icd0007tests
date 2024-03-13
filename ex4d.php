<?php

require_once 'common-functions.php';
require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080';

test('Default page has correct fields and values', function () {
    navigateTo(getUrl('ex4/state/'));

    assertPageContainsSelectWithName('list1');
    assertPageContainsSelectWithName('list2');

    assertPageContainsButtonWithName('list1_to_list2');
    assertPageContainsButtonWithName('list2_to_list1');

    $list1options = getSelectOptionValues('list1');
    $list2options = getSelectOptionValues('list2');

    assertThat($list1options, contains(['1', '2', '3', '4']));
    assertThat($list2options, contains(['5', '6']));
});

test('Can move items from list1 to list2', function () {
    navigateTo(getUrl('ex4/state/'));

    selectOptionWithText('list1', '2');

    clickButton('list1_to_list2');

    $list1options = getSelectOptionValues('list1');
    $list2options = getSelectOptionValues('list2');

    assertThat($list1options, contains(['1', '3', '4']));
    assertThat($list2options, contains(['5', '6', '2']));

    selectOptionWithText('list1', '1');

    clickButton('list1_to_list2');

    $list1options = getSelectOptionValues('list1');
    $list2options = getSelectOptionValues('list2');

    assertThat($list1options, contains(['3', '4']));
    assertThat($list2options, contains(['5', '6', '2', '1']));
});

test('Can move items from list2 to list1', function () {
    navigateTo(getUrl('ex4/state/'));

    selectOptionWithText('list2', '5');

    clickButton('list2_to_list1');

    $list1options = getSelectOptionValues('list1');
    $list2options = getSelectOptionValues('list2');

    assertThat($list1options, contains(['1', '2', '3', '4', '5']));
    assertThat($list2options, contains(['6']));

    selectOptionWithText('list2', '6');

    clickButton('list2_to_list1');

    $list1options = getSelectOptionValues('list1');
    $list2options = getSelectOptionValues('list2');

    assertThat($list1options, contains(['1', '2', '3', '4', '5', '6']));
    assertThat($list2options, contains([]));
});

function getUrl(string $relativeUrl): string {
    $baseUrl = removeLastSlash(BASE_URL);

    return "$baseUrl/$relativeUrl";
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(3));

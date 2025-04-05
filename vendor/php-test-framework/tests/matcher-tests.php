<?php

require_once __DIR__ . '/../public-api.php';

test('Contains matcher', function () {
    $text = 'abc 123 dcf';

    assertThat($text, containsString('123'));

    assertThrows(function () use ($text) {
        assertThat($text, containsString('123a'));
    });
});

test('Does not contain string matcher', function () {
    $text = 'abc 123 dcf';

    assertThat($text, doesNotContainString('123a'));

    assertThrows(function () use ($text) {
        assertThat($text, doesNotContainString('123'));
    });
});

test('Is matcher', function () {
    $text = 'abc';

    assertThat($text, is('abc'));

    assertThrows(function () use ($text) {
        assertThat($text, is('ab'));
    });
});

test('Contains once matcher', function () {
    $text = 'abcb';

    assertThat($text, containsStringOnce('a'));

    assertThrows(function () use ($text) {
        assertThat($text, containsStringOnce('b'));
    });
});

test('Contains in any order matcher', function () {
    $actual = [1, 2, 3];

    assertThat($actual, containsInAnyOrder([1, 2, 3]));
    assertThat($actual, containsInAnyOrder([2, 1, 3]));

    assertThrows(function () use ($actual) {
        assertThat($actual, containsInAnyOrder([]));
    });

    assertThrows(function () use ($actual) {
        assertThat($actual, containsInAnyOrder([1, 2, 4]));
    });
});

stf\runTests();

<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common.php';

const PROJECT_DIRECTORY = '';
const BASE_URL = 'http://localhost:8080';

function canSavePosts() {

    require_once 'ex3/ex6.php';

    $title = getRandomString(5);
    $text = getRandomString(10);

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

function canSavePostsContainingDifferentSymbols() {

    require_once 'ex3/ex6.php';

    $title = getRandomString(5);
    $text = getRandomString(10) . ".'\n;";

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

function canSendMultilineTextToDifferentReceiver() {
    navigateTo(BASE_URL . '/ex3/flow/sender.html');

    setTextFieldValue('text', "hello\nworld");

    clickButton('sendButton');

    assertPageContainsText("hello\nworld");
}

function canSendSimpleTextThroughRedirect() {
    navigateTo(BASE_URL . '/ex3/flow/sender.php');

    setTextFieldValue('text', 'hello');

    clickButton('sendButton');

    assertPageContainsText('Data was: hello');
}

function canSendMultilineTextThroughRedirect() {
    navigateTo(BASE_URL . '/ex3/flow/sender.php');

    setTextFieldValue('text', "hello\nworld");

    clickButton('sendButton');

    assertPageContainsText("Data was: hello\nworld");
}

function landingPageHasMenuWithCorrectLinks() {
    navigateTo(BASE_URL . '/ex3/calc/');

    assertPageContainsLinkWithId('c2f');
    assertPageContainsLinkWithId('f2c');
}

function f2cPageHasMenuWithCorrectLinks() {
    navigateTo(BASE_URL . '/ex3/calc/');

    clickLinkWithId('f2c');

    assertPageContainsLinkWithId('c2f');
    assertPageContainsLinkWithId('f2c');
}

function calculatesCelsiusToFahrenheit() {
    navigateTo(BASE_URL . '/ex3/calc/');

    setTextFieldValue('temperature', '20');

    clickButton('calculateButton');

    assertPageContainsText('is 68 decrees');
}

function calculatesFahrenheitToCelsius() {
    navigateTo(BASE_URL . '/ex3/calc/');

    clickLinkWithId('f2c');

    setTextFieldValue('temperature', '68');

    clickButton('calculateButton');

    assertPageContainsText('is 20 decrees');
}

#Helpers

setBaseUrl(BASE_URL);

extendIncludePath($argv, PROJECT_DIRECTORY);

stf\runTests(new stf\PointsReporter([9 => 1]));

<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common.php';

const PROJECT_DIRECTORY = '';
const BASE_URL = 'http://localhost:8080';

function canSaveNewPosts() {

    require_once 'ex4/post-data-functions.php';

    $title = getRandomString(5);
    $text = getRandomString(10);

    $post = new Post($title, $text);

    savePost($post);

    assertContains(getAllPosts(), $post);
}

function canDeletePosts() {

    require_once 'ex4/post-data-functions.php';

    $title = getRandomString(10);

    $post = new Post($title, '');

    $id = savePost($post);

    deletePostById($id);

    assertDoesNotContainPostWithTitle(getAllPosts(), $title);
}

function canUpdatePosts() {

    require_once 'ex4/post-data-functions.php';

    $titleOld = getRandomString(10);
    $titleNew = getRandomString(10);

    $post = new Post($titleOld, '');

    $id = savePost($post);

    assertContains(getAllPosts(), $post);

    $post->id = $id;
    $post->title = $titleNew;

    savePost($post);

    assertContains(getAllPosts(), $post);
    assertDoesNotContainPostWithTitle(getAllPosts(), $titleOld);
}

function confirmationWorksWithSimpleText() {
    navigateTo(BASE_URL . '/ex4/confirm/');

    setTextFieldValue('text', 'hello');

    clickButton('sendButton');

    clickLinkWithText('Confirm');

    assertPageContainsText('Confirmed: hello');
}

function confirmationWorksWithDifferentSymbols() {
    navigateTo(BASE_URL . '/ex4/confirm/');

    $text = "hello'\"\n";

    setTextFieldValue('text', $text);

    clickButton('sendButton');

    clickLinkWithText('Confirm');

    assertPageContainsText('Confirmed: ' . $text);
}

function checksCorrectRadio() {
    navigateTo(BASE_URL . '/ex4/radios.php');

    assertThat(getFieldValue('grade'), is('3'));

    navigateTo(BASE_URL . '/ex4/radios.php?grade=4');

    assertThat(getFieldValue('grade'), is('4'));
}

function calculatesArithmeticExpressions() {
    navigateTo(BASE_URL . '/ex4/calc/');

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
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintStackTrace(false);
setPrintPageSourceOnError(false);

#Helpers

setIncludePath();

stf\runTests(new stf\PointsReporter([7 => 1]));

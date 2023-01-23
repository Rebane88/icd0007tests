<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common-functions.php';

const PROJECT_DIRECTORY = '';

function testA1() {
}

function testA2() {

}

stf\runTests(getPassFailReporter(2));

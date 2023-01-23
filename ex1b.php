<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'common-functions.php';

function testB1() {
    
}

//stf\runTests(new stf\PointsReporter([1 => 1]));

stf\runTests(new stf\PassFailReporter(1));

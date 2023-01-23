<?php

require_once 'vendor/php-test-framework/public-api.php';

const BASE_URL = 'http://localhost:8080/ex2/nav/';

setBaseUrl(BASE_URL);

function cssIsCorrect() {
    $url = "http://localhost:8080/ex2/css/css.html";
    $cmd = sprintf(
        'google-chrome --headless --disable-gpu --no-sandbox --dump-dom %s', $url);

    exec($cmd, $output, $exitCode);

    if ($exitCode !== 0) {
        printf("error on running chrome\n");
        return;
    }

    $source = implode("\n", $output);

    if (strpos($source, '5 of 5 points') === false) {
        fail(ERROR_C01, "Css is not correct");
    }
}

stf\runTests(new stf\PointsReporter([7 => 1]));

<?php

require_once __DIR__ . '/../public-api.php';

use stf\browser\Path;

test('Absolute', function () {
    assertThat(path('/')->isAbsolute(), is(true));

    assertThat(path('a')->isAbsolute(), is(false));

    assertThat(path('/a')->isAbsolute(), is(true));

    assertThat(path('a/')->isAbsolute(), is(false));
});

test('As string', function () {
    assertThat(path('')->asString(), is(''));
    assertThat(path('a')->asString(), is('a'));
    assertThat(path('/')->asString(), is('/'));
    assertThat(path('/a')->asString(), is('/a'));
    assertThat(path('/a/')->asString(), is('/a/'));
    assertThat(path('a/')->asString(), is('a/'));
});

test('Normalize', function () {
    assertThat(Path::normalize(path(''))->asString(), is(''));

    assertThat(Path::normalize(path('.'))->asString(), is(''));
    assertThat(Path::normalize(path('..'))->asString(), is(''));

    assertThat(Path::normalize(path('./'))->asString(), is('/'));
    assertThat(Path::normalize(path('../'))->asString(), is('/'));

    assertThat(Path::normalize(path('../..'))->asString(), is(''));
    assertThat(Path::normalize(path('../../'))->asString(), is('/'));

    assertThat(Path::normalize(path('../'))->isAbsolute(), is(true));
});

test('CD', function () {
    assertThat(path('')->cd(path(''))->asString(), is(''));
    assertThat(path('a')->cd(path(''))->asString(), is('a'));
    assertThat(path('/')->cd(path(''))->asString(), is('/'));

    assertThat(path('')->cd(path('/a'))->asString(), is('/a'));
    assertThat(path('/')->cd(path('/a'))->asString(), is('/a'));
    assertThat(path('a')->cd(path('/b'))->asString(), is('/b'));

    assertThat(path('a')->cd(path('b'))->asString(), is('a/b'));
    assertThat(path('/a')->cd(path('b'))->asString(), is('/a/b'));

    assertThat(path('')->cd(path('.'))->asString(), is(''));
    assertThat(path('/')->cd(path('.'))->asString(), is('/'));
    assertThat(path('a')->cd(path('.'))->asString(), is('a'));

    assertThat(path('a/')->cd(path('.'))->asString(), is('a/'));
    assertThat(path('a/')->cd(path('./'))->asString(), is('a/'));
    assertThat(path('/a')->cd(path('..'))->asString(), is('/'));
    assertThat(path('/a')->cd(path('../'))->asString(), is('/'));
});

function path(?string $path) : Path {
    return new Path($path);
}

stf\runTests();
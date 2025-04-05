<?php

require_once __DIR__ . '/../public-api.php';

use stf\browser\page\RadioGroup;
use stf\browser\page\Checkbox;
use stf\browser\page\Select;

test('Radio group test', function () {
    $radio = new RadioGroup('r1');

    $radio->addOption("v1");
    $radio->addOption("v2");

    assertThat($radio->getValue(), is(null));

    $radio->selectOption("v1");

    assertThat($radio->getValue(), is('v1'));

    assertThat($radio->hasOption('v1'), is(true));
    assertThat($radio->hasOption('v2'), is(true));
    assertThat($radio->hasOption('v3'), is(false));
});

test('Select by value', function () {
    $select = new Select('s1');

    $select->addOption("v1", "Value 1", false);
    $select->addOption("v2", "Value 2", false);
    $select->addOption("v3", "Value 3", false);

    assertThat($select->getValue(), is('v1'));

    $select->selectOptionWithValue("v2");

    assertThat($select->getValue(), is('v2'));
});

test('Select by label', function () {
    $select = new Select('s1');

    $select->addOption("v1", "Value 1", false);
    $select->addOption("v2", "Value 2", false);
    $select->addOption("v3", "Value 3", false);

    assertThat($select->getValue(), is('v1'));

    $select->selectOptionWithText("Value 2");

    assertThat($select->getValue(), is('v2'));

    assertThat($select->hasOptionWithLabel("Value 1"), is(true));
    assertThat($select->hasOptionWithLabel("Value 4"), is(false));
});

test('Select last value if multiple options selected', function () {
    $select = new Select('s1', true);

    $select->addOption("v1", "Value 1", true);
    $select->addOption("v2", "Value 2", true);

    assertThat($select->getValue(), is('v2'));
});

test('Multiselect has no default', function () {
    $select = new Select('s1', true);

    $select->addOption("v1", "Value 1", false);

    assertThat($select->getValue(), is(''));
});

test('Checkbox with value', function () {
    $checkbox = new Checkbox('c1', '1');

    assertThat($checkbox->isChecked(), is(false));
    assertThat($checkbox->getValue(), is(null));

    $checkbox->check(true);

    assertThat($checkbox->isChecked(), is(true));
    assertThat($checkbox->getValue(), is('1'));
});

test('Checkbox with default value', function () {
    $checkbox = new Checkbox('c1', null, true);

    assertThat($checkbox->getValue(), is('on'));
});

stf\runTests();
<?php

require_once __DIR__ . '/../public-api.php';

use stf\browser\page\PageParser;
use stf\browser\page\PageBuilder;
use stf\browser\page\FormSet;
use stf\browser\page\NodeTree;

test('Builds radio buttons', function () {
    $html = '<form><input name="r1" type="radio" value="v1" />
                   <input name="r1" type="radio" checked value="v2" />
                   <input name="r1" type="radio" value="v3" /></form>';

    $radio = getFormSet($html)->getRadioByName('r1');

    assertThat($radio->getValue(), is('v2'));

    $radio->selectOption('v1');

    assertThat($radio->getValue(), is('v1'));
});

test('Builds checkboxes', function () {
    $html = '<form><input name="c1" type="checkbox" value="v1" />
                   <input name="c2" type="checkbox" checked value="v2" /></form>';

    $c1 = getFormSet($html)->getCheckboxByName('c1');
    $c2 = getFormSet($html)->getCheckboxByName('c2');

    assertThat($c1->getValue(), is(null));
    assertThat($c2->getValue(), is('v2'));
});

test('Builds file input', function () {
    $html = '<form enctype="multipart/form-data">
        <input name="f1" type="file" /></form>';

    $form = getFormSet($html)->findFormContainingField("f1");

    assertThat($form->getEnctype(), is('multipart/form-data'));

    $f1 = getFormSet($html)->getFileFieldByName('f1');

    assertThat($f1->getValue(), is(''));
});

test('Builds select', function () {
    $html = "<form>
             <select name='s1'>
             <option>\n Value 1 \n</option>
             <option selected value='v2'> \n Value 2 \n </option>
             <option value='v3'> \n Value 3 \n </option>
             </select>
             </form>";

    $select = getFormSet($html)->getSelectByName('s1');

    assertThat($select->getName(), is('s1'));

    assertThat($select->hasOptionWithLabel('Value 1'), is(true));
    assertThat($select->hasOptionWithLabel('Value 2'), is(true));
    assertThat($select->hasOptionWithLabel('Value 3'), is(true));
    assertThat($select->hasOptionWithLabel('Value 4'), is(false));

    assertThat($select->getValue(), is('v2'));
});

test('Builds multi select', function () {
    $html = "<form>
             <select name='ms1' multiple>
             <option>Value 1</option>
             <option>Value 1</option>
             </select>
             </form>";

    $select = getFormSet($html)->getSelectByName('ms1');

    assertThat($select->isMultiple(), is(true));
});

test('Builds select odd cases', function () {
    $html = "<form>
             <select name='s1'>
             <OPTION value> \n Value 1 \n </OPTION>
             <option value='v2'> \n Value 2 \n </option>
             <option VALUE='v3'>Value 3</option>
             <option> \n Value 4 \n </option>
             <option selected>Value 5</option>
             </select>
             </form>";

    $select = getFormSet($html)->getSelectByName('s1');

    assertThat($select->getName(), is('s1'));

    assertThat($select->hasOptionWithLabel('Value 1'), is(true));
    assertThat($select->hasOptionWithLabel('Value 2'), is(true));
    assertThat($select->hasOptionWithLabel('Value 3'), is(true));
    assertThat($select->hasOptionWithLabel('Value 6'), is(false));

    assertThat($select->getValue(), is('Value 5'));

    $select->selectOptionWithText('Value 1');
    assertThat($select->getValue(), is('Value 1'));

    $select->selectOptionWithText('Value 2');
    assertThat($select->getValue(), is('v2'));

    $select->selectOptionWithText('Value 3');
    assertThat($select->getValue(), is('v3'));

    $select->selectOptionWithText('Value 4');
    assertThat($select->getValue(), is('Value 4'));
});

test('Builds buttons', function () {
    $html = '<form action="?cmd=0">
                   <input type="submit" name="b1"
                          value="Button 1"
                          formaction="?cmd=1" />
                   <button type="submit" name="b2"
                           formaction="?cmd=2">Button 2</button></form>';

    $b1 = getFormSet($html)->getButtonByName('b1');

    assertThat($b1->getName(), is('b1'));
    assertThat($b1->getValue(), is('Button 1'));
    assertThat($b1->getLabel(), is('Button 1'));
    assertThat($b1->getFormAction(), is('?cmd=1'));

    $b2 = getFormSet($html)->getButtonByName('b2');
    assertThat($b2->getName(), is('b2'));
    assertThat($b2->getValue(), is(''));
    assertThat($b2->getLabel(), is('Button 2'));
    assertThat($b2->getFormAction(), is('?cmd=2'));
});

test('Builds buttons with value', function () {
    $html = '<form>
             <button type="submit" name="cmd"
                     value="c1">Cmd 1</button>
             <button type="submit" name="cmd"
                     value="c2">Cmd 2</button></form>';

    $button = getFormSet($html)->getButtonByNameAndValue('cmd', 'c1');

    assertThat($button->getName(), is('cmd'));
    assertThat($button->getValue(), is('c1'));
});

test('Builds submit button', function () {
    $html = '<form>
             <button type="reset" name="cmd"
                     value="c1">Cmd 1</button>
             <button type="submit" name="cmd"
                     value="c2">Cmd 2</button>
             <button name="cmd"
                     value="c3">Cmd 3</button>
             </form>';

    $formSet = getFormSet($html);

    $button1 = $formSet->getButtonByNameAndValue('cmd', 'c1');
    $button2 = $formSet->getButtonByNameAndValue('cmd', 'c2');
    $button3 = $formSet->getButtonByNameAndValue('cmd', 'c3');

    assertThat($button1, is(null));
    assertThat($button2, isNot(null));
    assertThat($button3, isNot(null));
});

test('Builds text area', function () {
    $html = '<form><textarea name="a1"> Hello </textarea></form>';

    $field = getFormSet($html)->getTextFieldByName('a1');

    assertThat($field->getName(), is('a1'));
    assertThat($field->getValue(), is(' Hello '));
});

test('Handles multiple forms', function () {
    $html = '<form>
                 <input name="t1" type="radio">
                 <input name="t1" type="radio">
             </form>
             <form>
                 <input name="t2">
             </form>';

    $field1 = getFormSet($html)->getRadioByName('t1');
    $field2 = getFormSet($html)->getTextFieldByName('t2');

    assertThat($field1->getName(), is('t1'));
    assertThat($field2->getName(), is('t2'));
});

test('Throws when different forms have element with same name', function () {
    $html = '<form><input name="t1"></form>
             <form><input name="t1"></form>';

    assertThrows(function () use ($html) {
        getFormSet($html)->getTextFieldByName('t1');
    });
});

function getFormSet(string $html) : FormSet {
    $parser = new PageParser($html);

    $nodeTree = new NodeTree($parser->getNodeTree());

    return (new PageBuilder($nodeTree, $html))->getPage()->getFormSet();
}

stf\runTests();
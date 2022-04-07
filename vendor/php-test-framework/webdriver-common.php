<?php

// require_once __DIR__ . '/vendor/php-webdriver/autoload.php';
// require_once __DIR__ . '/common.php';

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Chrome\ChromeOptions;

const SELENIUM_SERVER_URL = 'http://localhost:4444/';

const MAX_WAIT_TIME = 3;
const POLL_FREQUENCY = 200;

$driver = null;

function getInChrome($url) {
    global $driver;

    $driver = null;

    getDriver()->get($url);
}

function closeBrowser() {
    getDriver()->quit();
}

function assertLinkById($id) {
    getElement(WebDriverBy::id($id));
}

function getElement($selector) : ?RemoteWebElement {
    $message = sprintf("Did not find element %s = '%s'",
        $selector->getMechanism(), $selector->getValue());

    try {
        getDriver()->wait(MAX_WAIT_TIME, POLL_FREQUENCY)->until(
            WebDriverExpectedCondition::presenceOfElementLocated($selector), $message
        );
    } catch (Facebook\WebDriver\Exception\NoSuchElementException $e) {

        return null;
    }

    return getDriver()->findElement($selector);
}

function clickLinkById($id) {
    clickAndWaitUrlChange(WebDriverBy::id($id));
}

function clickAndWaitUrlChange($selector) {
    $element = getElement($selector);

    // sometimes the element is found but if clicked too soon the click fails.
    usleep(200000);

    $previousUrl = getDriver()->getCurrentURL();

    $element->click();

    getDriver()->wait(MAX_WAIT_TIME, POLL_FREQUENCY)->until(
        function () use ($previousUrl) {
            $tmpUrl = getDriver()->getCurrentURL();

            return $previousUrl !== $tmpUrl;
        },
        sprintf("Url did not change from %s", $previousUrl)
    );
}

function setFieldByName($name, $value) {
    assertFieldByName($name);

    setValueBySelector(WebDriverBy::name($name), $value);
}

function assertFieldByName($name) {
    getElement(WebDriverBy::name($name));
}

function clickByName($name) {
    clickAndWaitUrlChange(WebDriverBy::name($name));
}

function clickLinkByText($text) {
    clickAndWaitUrlChange(WebDriverBy::linkText($text));
}

function assertNoText($textToMatch) {
    $text = getDriver()->getPageSource();

    $matchCount = substr_count($text, $textToMatch);

    if ($matchCount !== 0) {
        fail(ERROR_C01,
            sprintf("Expecting not to find text '%s' but found it", $textToMatch));
    }
}

function assertSingleMatch($textToMatch) {
    $matchCount = 0;

    getDriver()->wait(MAX_WAIT_TIME, POLL_FREQUENCY)->until(
        function () use ($textToMatch, $matchCount) {
            $text = getDriver()->getPageSource();

            $matchCount = substr_count($text, $textToMatch);

            return $matchCount === 1;
        },
        sprintf(
            "Expecting to find text '%s' once but found it %s times",
            $textToMatch, $matchCount)
    );
}

function setValueBySelector($selector, $value) {

    getDriver()->wait(MAX_WAIT_TIME, POLL_FREQUENCY)->until(
        function () use ($selector, $value) {
            $input = getDriver()->findElement($selector);

            $input->clear()->sendKeys($value);

            $readValue = $input->getAttribute('value');

            return $readValue === $value;
        },
        sprintf("Could not set value to element %s = '%s'",
            $selector->getMechanism(), $selector->getValue())
    );
}

function getDriver() : RemoteWebDriver {
    global $driver;


    if ($driver == null) {
        $driver = createDriver();
    }

    return $driver;
}

function createDriver() : RemoteWebDriver {
    $options = new ChromeOptions();
    $options->addArguments(
        ['headless', 'no-sandbox', 'disable-gpu']);

    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

    return RemoteWebDriver::create(SELENIUM_SERVER_URL, $capabilities);
}
<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'vendor/php-test-framework/Employee.php';
require_once 'vendor/php-test-framework/Task.php';

const BASE_URL = 'http://localhost:8080/';

test('Displays error when submitting invalid employee data', function () {
    gotoLandingPage();

    clickEmployeeFormLink();

    clickButton('submitButton');

    assertPageContainsElementWithId('error-block');

    $employee = getSampleEmployee(); // sample employee with random values

    setTextFieldValue('firstName', $employee->firstName);
    setTextFieldValue('lastName', $employee->lastName);

    clickEmployeeFormSubmitButton();

    assertPageContainsElementWithId('message-block');
    assertPageDoesNotContainElementWithId('error-block');
});

test('On validation error employee form is filled with inserted data', function () {
    gotoLandingPage();

    clickEmployeeFormLink();

    setTextFieldValue('firstName', 'a');
    setTextFieldValue('lastName', 'b');

    clickButton('submitButton');

    assertThat(getFieldValue('firstName'), is('a'));
    assertThat(getFieldValue('lastName'), is('b'));
});

test('Displays error when submitting invalid task data', function () {
    gotoLandingPage();

    clickTaskFormLink();

    clickButton('submitButton');

    assertPageContainsElementWithId('error-block');

    setTextFieldValue('description', 'some description');

    clickButton('submitButton');

    assertPageDoesNotContainElementWithId('error-block');
    assertPageContainsElementWithId('message-block');
});

test('On validation error task form is filled with inserted data', function () {
    gotoLandingPage();

    clickTaskFormLink();

    setTextFieldValue('description', 'a');

    clickButton('submitButton');

    assertThat(getFieldValue('description'), is('a'));
});

test('Can delete inserted employees', function () {
    gotoLandingPage();

    clickEmployeeFormLink();

    $employee = getSampleEmployee();

    setTextFieldValue('firstName', $employee->firstName);
    setTextFieldValue('lastName', $employee->lastName);

    clickEmployeeFormSubmitButton();

    $employeeId = getEmployeeIdByName(
        $employee->firstName . ' ' . $employee->lastName);

    clickLinkWithId('employee-edit-link-' . $employeeId);

    clickEmployeeFormDeleteButton();

    assertThat(getPageText(), doesNotContainString($employee->firstName));
});

test('Can delete inserted tasks', function () {
    gotoLandingPage();

    clickTaskFormLink();

    $task = getSampleTask();

    setTextFieldValue('description', $task->description);

    clickTaskFormSubmitButton();

    $taskId = getTaskIdByDescription($task->description);

    clickLinkWithId('task-edit-link-' . $taskId);

    clickTaskFormDeleteButton();

    assertThat(getPageText(), doesNotContainString($task->description));
});

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(6));

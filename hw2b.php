<?php

require_once 'vendor/php-test-framework/public-api.php';
require_once 'vendor/php-test-framework/Employee.php';

const BASE_URL = 'http://localhost:8080';

function canUploadProfilePicture() {
    gotoLandingPage();

    clickEmployeeFormLink();

    $employee = getSampleEmployee(); // sample employee with random values

    setTextFieldValue('firstName', $employee->firstName);
    setTextFieldValue('lastName', $employee->lastName);
    setFileFieldValues('picture',
        $employee->profilePicture,
        $employee->profilePictureContents);

    clickEmployeeFormSubmitButton();

    $employeeId = getEmployeeIdByName(
        $employee->firstName . ' ' . $employee->lastName);

    assertThat($employeeId, isNot(null),
        'Did not find employee id');

    $imageUrl = getProfilePictureUrl($employeeId);

    assertThat($imageUrl, isNot(null),
        'Did not find profile picture url');

    $imageContents = getImage($imageUrl);

    assertThat($imageContents, is($employee->profilePictureContents));
}

setBaseUrl(BASE_URL);
setLogRequests(false);
setLogPostParameters(false);
setPrintPageSourceOnError(false);

stf\runTests(getPassFailReporter(1));

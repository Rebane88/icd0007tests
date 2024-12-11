<?php

spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);

    if (str_starts_with($className, 'tplLib')) {
        $basePath = __DIR__ . '/parser';
        array_shift($parts);
    } else if (str_starts_with($className, 'Facebook')) {
        $basePath = __DIR__ . '/php-webdriver/webdriver/lib';
        array_shift($parts);
        array_shift($parts);
    } else if (str_starts_with($className, 'Symfony\\Polyfill')) {
        $basePath = __DIR__ . '/symfony/polyfill-mbstring';
        array_shift($parts);
        array_shift($parts);
        array_shift($parts);
    } else if (str_starts_with($className, 'Symfony\\Component')) {
        $basePath = __DIR__ . '/symfony/process';
        array_shift($parts);
        array_shift($parts);
        array_shift($parts);
    } else if (str_starts_with($className, 'Simple')) {
        $basePath = __DIR__ . '/simpletest';
    } else {
        $basePath = __DIR__;
        array_shift($parts);
    }

    $filePath = sprintf('%s/%s.php', $basePath, implode('/', $parts));

    try {
        require_once $filePath;
    } catch (Error $_) {
        var_dump($className);
        var_dump($filePath);
    }
});

<?php

function getProjectDirectory(): string {
    global $argv;

    return removeLastSlash(getProjectPath($argv, PROJECT_DIRECTORY));
}

function removeLastSlash(string $path): string {
    return preg_replace('/\/$/', '', $path);
}

function getRepoSize($path): int {
    chdir($path);

    $filter = function ($file) {
        return ! preg_match(
            '/^(\\.\\/\\.git)|vendor|\\.png|\\.jpg|\\.ttf/',
            $file->getPathName());
    };

    $it = new RecursiveDirectoryIterator('.');
    $it = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($it, $filter));

    $size = 0;
    foreach($it as $file) {
        if (is_file($file)) {
            $size += filesize($file);
        }
    }

    return $size;
}

function getFileCount($path, $extension): int {

    $filter = function ($file) {
        return ! preg_match('/^(\\.\\/ex\\d)|vendor$/', $file->getPathName());
    };

    chdir($path);

    $it = new RecursiveDirectoryIterator('.');
    $it = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator($it, $filter));
    $it = new RegexIterator($it, '/\.(\w+)$/i', RegexIterator::GET_MATCH);

    $count = 0;
    foreach($it as $each) {
        if (strtolower($each[1]) === $extension) {
            $count++;
        }
    }

    return $count;
}

function readJsonFileFrom(string $path) {
    $infoFile = "$path/info.json";

    if (!file_exists($infoFile)) {
        die("can't find info.json from $path" . PHP_EOL);
    }

    $string = file_get_contents($infoFile);

    return json_decode($string, true);
}
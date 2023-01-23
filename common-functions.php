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

    $it = new RecursiveDirectoryIterator('.');
    $it = new RecursiveIteratorIterator($it);

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


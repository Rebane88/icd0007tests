<?php

use php_test_framework\RepositoryFile;

require_once 'vendor/php-test-framework/RepositoryFile.php';

function getProjectDirectory(): string {
    global $argv;

    return removeLastSlash(getProjectPath($argv, PROJECT_DIRECTORY));
}

function containsHtmlTags($contents): string {
    return preg_match('/<[a-zA-Z]+(>|\s+)/', $contents);
}

function containsPhpTags($contents): string {
    return preg_match('/<\?(=|php)/', $contents);
}

function removeLastSlash(string $path): string {
    return rtrim($path, '/');
}

function getRepoSize($path): int {
    $size = 0;
    foreach(getProjectFiles($path) as $file) {
        if (!$file->isGraphicsFile()) {
            $size += filesize($file->getAbsolutePath());
        }
    }

    return $size;
}

function getFileCount($path, $extension): int {
    $count = 0;
    foreach(getProjectFiles($path) as $file) {
        if ($file->getExtension() === $extension) {
            $count++;
        }
    }

    return $count;
}

function getProjectFiles($path): array {
    $it = new RecursiveDirectoryIterator($path);
    $it = new RecursiveIteratorIterator($it);

    $files = [];
    foreach($it as $each) {
        $file = new RepositoryFile($each->getPathName(), $path);

        if (is_file($file->getAbsolutePath()) && $file->isProjectFile()) {
            $files[] = $file;
        }
    }

    return $files;
}

function readJsonFileFrom(string $path) {
    $infoFile = "$path/info.json";

    if (!file_exists($infoFile)) {
        die("can't find info.json from $path" . PHP_EOL);
    }

    $string = file_get_contents_utf8($infoFile);

    return json_decode($string, true);
}

function file_get_contents_utf8($fn): string {
    $content = file_get_contents($fn);

    $detected_encoding = mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true);
    if ($detected_encoding != 'UTF-8') {
        $content = mb_convert_encoding($content, 'UTF-8', $detected_encoding);
    }

    if (substr($content, 0, 3) == pack('CCC', 0xEF, 0xBB, 0xBF)) {
        $content = substr($content, 3);
    }

    return $content;
}

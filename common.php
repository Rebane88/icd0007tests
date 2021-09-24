<?php

function assertContains(array $allPosts, Post $post) {
    foreach ($allPosts as $each) {
        if ($each->title === $post->title && $each->text === $post->text) {
            return;
        }
    }

    throw new stf\FrameworkException(ERROR_C01, "Did not find saved post");
}

function assertDoesNotContainPostWithTitle(array $allPosts, string $title) {
    foreach ($allPosts as $each) {
        if ($each->title === $title) {
            throw new stf\FrameworkException(ERROR_C01,
                sprintf("Found post with title '%s'", $title));
        }
    }
}

function extendIncludePath(array $argv, string $userDefinedDir) {
    $path = count($argv) === 2 ? $argv[1] : $userDefinedDir;

    if (!$path) {
        die("Please specify your project's directory in constant PROJECT_DIRECTORY");
    }

    $path = realpath($path);

    if (!file_exists($path)) {
        die("Value in PROJECT_DIRECTORY is not correct directory");
    }

    set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}

function getProjectDirectory() : string {
    global $argc, $argv;

    $path = $argc === 2 ? $argv[1] : PROJECT_DIRECTORY;

    if (!$path) {
        die("Please specify your projects directory in constant PROJECT_DIRECTORY");
    }

    $path = realpath($path);

    if (!file_exists($path)) {
        die("Value in PROJECT_DIRECTORY is not correct directory");
    }

    return $path;
}

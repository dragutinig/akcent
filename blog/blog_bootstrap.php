<?php

function blogBasePath(): string
{
    static $basePath = null;

    if ($basePath !== null) {
        return $basePath;
    }

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $marker = '/blog/php/';

    $position = strpos($scriptName, $marker);
    if ($position !== false) {
        $basePath = substr($scriptName, 0, $position) . '/blog';
    } else {
        $basePath = '/blog';
    }

    return rtrim($basePath, '/');
}

function blogUrl(string $path = ''): string
{
    $base = blogBasePath();
    $normalizedPath = ltrim($path, '/');

    if ($normalizedPath === '') {
        return $base . '/';
    }

    return $base . '/' . $normalizedPath;
}

function siteUrl(string $path = ''): string
{
    $normalizedPath = ltrim($path, '/');
    if ($normalizedPath === '') {
        return '/';
    }

    return '/' . $normalizedPath;
}

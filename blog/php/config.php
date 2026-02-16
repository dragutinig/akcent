<?php

function getEnvOrDefault(string $key, string $default): string
{
    $value = getenv($key);
    return $value === false || $value === '' ? $default : $value;
}

function getProjectBasePath(): string
{
    $configuredBasePath = getenv('AKCENT_BASE_PATH');
    if ($configuredBasePath !== false && $configuredBasePath !== '') {
        $normalized = '/' . trim($configuredBasePath, '/');
        return $normalized === '/' ? '' : $normalized;
    }

    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $marker = '/blog/php/';
    $position = strpos($scriptName, $marker);

    if ($position === false) {
        return '';
    }

    $basePath = rtrim(substr($scriptName, 0, $position), '/');
    return $basePath === '' ? '' : $basePath;
}

function getScheme(): string
{
    $https = $_SERVER['HTTPS'] ?? '';
    return (!empty($https) && $https !== 'off') ? 'https' : 'http';
}

function getHostWithPort(): string
{
    return $_SERVER['HTTP_HOST'] ?? 'localhost';
}

function getSiteBaseUrl(): string
{
    $configuredSiteUrl = getenv('AKCENT_SITE_URL');
    if ($configuredSiteUrl !== false && $configuredSiteUrl !== '') {
        return rtrim($configuredSiteUrl, '/');
    }

    return getScheme() . '://' . getHostWithPort() . getProjectBasePath();
}

function getBlogBasePath(): string
{
    return getProjectBasePath() . '/blog';
}

function getBlogBaseUrl(): string
{
    return getSiteBaseUrl() . '/blog';
}

function getDbConfig(): array
{
    $isProductionHost = isset($_SERVER['HTTP_HOST']) && preg_match('/(^|\.)akcent\.rs$/i', $_SERVER['HTTP_HOST']);

    $defaultUser = $isProductionHost ? 'akcentrs_blogdatabase' : 'root';
    $defaultPassword = $isProductionHost ? 'Dragigagi1' : '';
    $defaultDbName = $isProductionHost ? 'akcentrs_blogdatabase' : 'akcentrs';

    return [
        'host' => getEnvOrDefault('AKCENT_DB_HOST', 'localhost'),
        'username' => getEnvOrDefault('AKCENT_DB_USER', $defaultUser),
        'password' => getEnvOrDefault('AKCENT_DB_PASS', $defaultPassword),
        'dbname' => getEnvOrDefault('AKCENT_DB_NAME', $defaultDbName),
        'dbname' => getEnvOrDefault('AKCENT_DB_NAME', 'akcentrs_blogdatabase'),
    ];
}

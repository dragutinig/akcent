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



function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        @ini_set('session.cookie_httponly', '1');
        @ini_set('session.cookie_secure', $isHttps ? '1' : '0');
        @ini_set('session.cookie_samesite', 'Lax');
        session_set_cookie_params(0, '/; samesite=Lax', '', $isHttps, true);
    }

    session_start();
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
    ];
}


function resolveImageUrl(string $rawPath): string
{
    $rawPath = trim($rawPath);
    if ($rawPath === '') {
        return getBlogBasePath() . '/uploads/placeholder.jpg';
    }

    if (preg_match('#^https?://#i', $rawPath)) {
        return $rawPath;
    }

    if ($rawPath[0] === '/') {
        return $rawPath;
    }

    if (strpos($rawPath, '../') === 0) {
        return getBlogBasePath() . '/' . ltrim(substr($rawPath, 3), '/');
    }

    if (strpos($rawPath, 'uploads/') === 0) {
        return getBlogBasePath() . '/' . $rawPath;
    }

    return getBlogBasePath() . '/uploads/' . ltrim($rawPath, '/');
}

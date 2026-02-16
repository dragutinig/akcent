<?php

require_once 'config.php';

start_secure_session();

$inactive = 7200;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_unset();
    session_destroy();
    header('Location: ' . getBlogBasePath() . '/php/login.php');
    exit();
}

$fingerprint = hash('sha256', (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '') . '|' . ($_SERVER['HTTP_USER_AGENT'] ?? ''));
if (isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] !== $fingerprint) {
    session_unset();
    session_destroy();
    header('Location: ' . getBlogBasePath() . '/php/login.php?force=1');
    exit();
}

$_SESSION['fingerprint'] = $fingerprint;
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . getBlogBasePath() . '/php/login.php');
    exit();
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    die('Pristup odbijen! Nemate administratorske privilegije.');
}

function admin_esc($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

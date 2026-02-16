<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$inactive = 21600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    die('Pristup odbijen! Nemate administratorske privilegije.');
}

function admin_esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

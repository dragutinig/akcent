<?php

require_once 'Database.php';
require_once 'User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(strip_tags($_POST['username']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $password = htmlspecialchars(strip_tags($_POST['password']));
    $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));

    if ($password !== $confirm_password) {
        echo "Lozinke se ne poklapaju!";
        exit();
    }

    $user->username = $username;
    $user->email = $email;
    $user->password_hash = $password;
    $user->role = 'user';

    if ($user->emailExists()) {
        echo "Korisnik sa tim email-om već postoji.";
    } elseif ($user->register()) {
        echo "Registracija uspešna!";
        header("Location: dashboard.php");
    } else {
        echo "Došlo je do greške pri registraciji.";
    }
}

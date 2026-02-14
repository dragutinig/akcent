<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'Database.php';
require_once 'User.php';

// Trajanje sesije u sekundama (1 sat)
$inactive = 21600;

// Proveravamo da li je korisnik bio neaktivan
if (isset($_SESSION['last_activity'])) {
    $session_life = time() - $_SESSION['last_activity'];
    if ($session_life > $inactive) {
        // Uništavanje sesije nakon isteka vremena
        session_unset();
        session_destroy();
        header("Location: login.php"); // Preusmeravanje na stranicu za login
        exit();
    }
}

// Ažuriramo poslednje vreme aktivnosti
$_SESSION['last_activity'] = time();

// Povezivanje sa bazom
$database = new Database();
$db = $database->connect();

// Kreiramo instancu korisnika
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user->email = htmlspecialchars(strip_tags($_POST['email']));
    $password = htmlspecialchars(strip_tags($_POST['password'])); // Unesena lozinka

    // Proverite da li korisnik postoji
    if ($user->emailExists()) {
        $user->password_hash = $password; // Postavljanje unete lozinke za proveru

        if ($user->login()) {
            // Postavljamo podatke u sesiju
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;

            // Ažuriramo poslednje vreme aktivnosti
            $_SESSION['last_activity'] = time();

            // Preusmeravamo korisnika na dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Email ili lozinka nisu tačni.";
        }
    } else {
        echo "Korisnik sa ovom email adresom nije pronađen.";
    }
}

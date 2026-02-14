<?php
require_once 'Database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $db = (new Database())->connect();
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?message=Korisnik uspešno obrisan!");
    } else {
        echo "Greška pri brisanju korisnika: " . $stmt->error;
    }
}
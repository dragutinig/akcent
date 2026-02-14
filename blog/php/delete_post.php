<?php
require_once 'Database.php';
session_start();

// Provera da li je korisnik prijavljen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->connect();

    $query = "DELETE FROM posts WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?message=Post uspešno obrisan!");
    } else {
        echo "Greška pri brisanju posta: " . $db->error;
    }
}

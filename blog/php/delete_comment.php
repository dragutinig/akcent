<?php
require_once 'Database.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    echo "Pristup odbijen!";
    exit();
}

if (isset($_GET['id'])) {
    $commentId = $_GET['id'];

    $database = new Database();
    $db = $database->connect();

    $query = "DELETE FROM comments WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $commentId);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Greška prilikom brisanja komentara.";
    }
}

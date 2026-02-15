<?php
if (isset($_FILES['file'])) {
    $upload_dir = '../uploads/';
header('Content-Type: application/json; charset=utf-8');
require_once 'upload_utils.php';

    // Kreiraj folder ako ne postoji
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
try {
    if (!isset($_FILES['file'])) {
        throw new RuntimeException('Nije poslata nijedna datoteka.');
    }

    $file_name = basename($_FILES['file']['name']);
    $target_file = $upload_dir . $file_name;

    // Provera ekstenzije fajla
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        http_response_code(400);
        echo json_encode(['error' => 'Dozvoljeni formati su: JPG, JPEG, PNG, GIF.']);
        exit();
    }

    // Upload slike
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        echo json_encode(['location' => $target_file]); // Vraćamo putanju slike
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Greška pri uploadu slike.']);
    }
} else {
    $url = processUploadedImage($_FILES['file']);
    echo json_encode(['location' => $url], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['error' => 'Nije poslata nijedna datoteka.']);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}?>
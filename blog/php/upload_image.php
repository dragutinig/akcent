<?php
if (isset($_FILES['file'])) {
    $upload_dir = '../uploads/';

    // Kreiraj folder ako ne postoji
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
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
    http_response_code(400);
    echo json_encode(['error' => 'Nije poslata nijedna datoteka.']);
}

<?php
require_once __DIR__ . '/../Database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("DB konekcija nije uspela.\n");
}

echo "[1/3] Kreiram post_comments tabelu...\n";
$conn->query("CREATE TABLE IF NOT EXISTS post_comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT UNSIGNED NOT NULL,
    author_name VARCHAR(120) NOT NULL,
    author_email VARCHAR(190) DEFAULT NULL,
    comment_text TEXT NOT NULL,
    status ENUM('approved','pending','spam') NOT NULL DEFAULT 'approved',
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_post_comments_post_status (post_id, status, created_at),
    CONSTRAINT fk_post_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

echo "[2/3] Normalizujem putanje slika projekata...\n";
$res = $conn->query('SELECT id, image_path FROM project_images');
$upd = $conn->prepare('UPDATE project_images SET image_path = ? WHERE id = ?');
$updatedImages = 0;
while ($row = $res->fetch_assoc()) {
    $id = (int) $row['id'];
    $path = trim((string) $row['image_path']);
    $newPath = $path;

    $normalized = str_replace('\\\\', '/', $path);
    if (preg_match('#^[A-Za-z]:/#', $normalized)) {
        $file = basename($normalized);
        if ($file !== '') {
            $newPath = 'blog/uploads/projects/' . $file;
        }
    } elseif (strpos($normalized, '../uploads/') === 0) {
        $newPath = 'blog/' . ltrim(substr($normalized, 3), '/');
    } elseif (strpos($normalized, 'uploads/') === 0) {
        $newPath = 'blog/' . ltrim($normalized, '/');
    }

    if ($newPath !== $path) {
        $upd->bind_param('si', $newPath, $id);
        $upd->execute();
        $updatedImages++;
    }
}

echo "Ažurirano image_path redova: {$updatedImages}\n";

echo "[3/3] Normalizujem putanje 3D modela projekata...\n";
$res2 = $conn->query('SELECT id, model_path FROM projects WHERE model_path IS NOT NULL AND model_path <> ""');
$upd2 = $conn->prepare('UPDATE projects SET model_path = ? WHERE id = ?');
$updatedModels = 0;
while ($row = $res2->fetch_assoc()) {
    $id = (int) $row['id'];
    $path = trim((string) $row['model_path']);
    $newPath = $path;

    $normalized = str_replace('\\\\', '/', $path);
    if (strpos($normalized, 'project-models/') === 0) {
        $newPath = 'blog/' . $normalized;
    }

    if ($newPath !== $path) {
        $upd2->bind_param('si', $newPath, $id);
        $upd2->execute();
        $updatedModels++;
    }
}

echo "Ažurirano model_path redova: {$updatedModels}\n";
echo "Migracija završena.\n";

$conn->close();

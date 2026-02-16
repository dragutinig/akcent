<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Database.php';
require_once 'config.php';
require_once 'admin_bootstrap.php';

function generate_slug(string $text): string
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    return trim($text, '-') ?: 'post';
}

$database = new Database();
$db = $database->connect();

$post_id = (int) ($_GET['post_id'] ?? 0);
if ($post_id <= 0) {
    header('Location: dashboard.php?error=Post ID nije prosleđen');
    exit();
}

$post_stmt = $db->prepare('SELECT * FROM posts WHERE id = ?');
$post_stmt->bind_param('i', $post_id);
$post_stmt->execute();
$post = $post_stmt->get_result()->fetch_assoc();
if (!$post) {
    header('Location: dashboard.php?error=Post ne postoji');
    exit();
}

$categories = [];
$categories_result = $db->query('SELECT * FROM categories ORDER BY name ASC');
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$tag_stmt = $db->prepare('SELECT t.name FROM tags t JOIN posttags pt ON t.id = pt.tag_id WHERE pt.post_id = ?');
$tag_stmt->bind_param('i', $post_id);
$tag_stmt->execute();
$tag_result = $tag_stmt->get_result();
$tags = [];
while ($row = $tag_result->fetch_assoc()) {
    $tags[] = $row['name'];
}
$tags_string = implode(', ', $tags);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $post_url = trim($_POST['url'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $slug = generate_slug($post_url !== '' ? $post_url : $title);
    $status = $_POST['status'] ?? 'draft';
    $user_id = (int) $_SESSION['user_id'];
    $category_id = !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null;
    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_title = trim($_POST['meta_title'] ?? '');
    $tags = array_map('trim', explode(',', $_POST['tags'] ?? ''));

    $featured_image = $post['featured_image'];
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = time() . '-' . preg_replace('/\s+/', '-', basename($_FILES['featured_image']['name']));
        $target_file = $upload_dir . $image_name;

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions, true)) {
            die('Dozvoljeni formati su: JPG, JPEG, PNG, GIF, WEBP.');
        }

        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
            $featured_image = $target_file;
        }
    }

    $query = 'UPDATE posts SET title = ?, slug = ?, content = ?, featured_image = ?, user_id = ?, category_id = ?, status = ?, published_at = ?, updated_at = NOW(), meta_description = ?, meta_title = ?, post_url = ? WHERE id = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssssissssssi', $title, $slug, $content, $featured_image, $user_id, $category_id, $status, $published_at, $meta_description, $meta_title, $post_url, $post_id);

    if ($stmt->execute()) {
        $delete_tags_query = 'DELETE FROM posttags WHERE post_id = ?';
        $delete_tags_stmt = $db->prepare($delete_tags_query);
        $delete_tags_stmt->bind_param('i', $post_id);
        $delete_tags_stmt->execute();

        foreach ($tags as $tag_name) {
            if ($tag_name === '') {
                continue;
            }

            $tag_query = 'SELECT id FROM tags WHERE name = ?';
            $tag_stmt = $db->prepare($tag_query);
            $tag_stmt->bind_param('s', $tag_name);
            $tag_stmt->execute();
            $tag_result = $tag_stmt->get_result();

            if ($tag_result->num_rows > 0) {
                $tag_id = (int) $tag_result->fetch_assoc()['id'];
            } else {
                $tag_slug = generate_slug($tag_name);
                $insert_tag_query = 'INSERT INTO tags (name, slug, created_at, updated_at) VALUES (?, ?, NOW(), NOW())';
                $insert_tag_stmt = $db->prepare($insert_tag_query);
                $insert_tag_stmt->bind_param('ss', $tag_name, $tag_slug);
                $insert_tag_stmt->execute();
                $tag_id = $insert_tag_stmt->insert_id;
            }

            $post_tag_query = 'INSERT INTO posttags (post_id, tag_id) VALUES (?, ?)';
            $post_tag_stmt = $db->prepare($post_tag_query);
            $post_tag_stmt->bind_param('ii', $post_id, $tag_id);
            $post_tag_stmt->execute();
        }

        header('Location: dashboard.php?message=Post uspešno ažuriran!');
        exit();
    }

    die('Greška pri ažuriranju posta: ' . $db->error);
}
?>
<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izmena posta</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css">
    <script src="../js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 420,
            menubar: false,
            plugins: ['anchor','autolink','charmap','codesample','emoticons','image','link','lists','media','paste','searchreplace','table','visualblocks','wordcount'],
            paste_as_text: true,
            forced_root_block: 'p',
            remove_trailing_brs: true,
            invalid_elements: 'font',
            content_css: '../css/editor-content.css',
            content_style: 'body{max-width:840px;margin:16px auto;padding:0 14px;} p{margin:0 0 1em;line-height:1.75;}',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | link image media table | align lineheight | checklist numlist bullist | emoticons charmap | removeformat'
        });
    </script>
</head>

<body>
    <?php $current = 'create'; ?>
    <main class="admin-shell">
        <?php include "admin_sidebar.php"; ?>
        <section class="admin-content">
        <section class="topbar">
            <div>
                <h1>Izmena posta #<?= (int) $post_id; ?></h1>
                <p class="muted">Ažuriraj sadržaj, SEO podatke, status i sliku.</p>
            </div>
            <div style="display:flex; gap:8px;">
                <a class="btn btn-secondary" href="dashboard.php">← Dashboard</a>
                <a class="btn btn-secondary" href="create-post.php">+ Novi post</a>
                <a class="btn btn-info" target="_blank" href="preview.php?post_id=<?= (int) $post_id; ?>">Preview nacrta</a>
            </div>
        </section>

        <section class="section" style="margin-top:16px;">
            <div class="section-header"><h2>Forma za uređivanje</h2></div>
            <div style="padding:14px;">
                <form action="edit_post.php?post_id=<?= (int) $post_id; ?>" method="POST" enctype="multipart/form-data" class="form-grid">
                    <div class="form-group full">
                        <label for="title">Naslov</label>
                        <input type="text" name="title" id="title" value="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group full">
                        <label for="content">Sadržaj</label>
                        <textarea name="content" id="content"><?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="meta_title">Meta title</label>
                        <input type="text" name="meta_title" id="meta_title" value="<?= htmlspecialchars($post['meta_title'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="url">URL / slug osnova</label>
                        <input type="text" name="url" id="url" value="<?= htmlspecialchars($post['post_url'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group full">
                        <label for="meta_description">Meta description</label>
                        <textarea name="meta_description" id="meta_description"><?= htmlspecialchars($post['meta_description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Kategorija</label>
                        <select name="category_id" id="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int) $category['id']; ?>" <?= ((int) $category['id'] === (int) $post['category_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : ''; ?>>Nacrt</option>
                            <option value="published" <?= $post['status'] === 'published' ? 'selected' : ''; ?>>Objavljen</option>
                        </select>
                    </div>

                    <div class="form-group full">
                        <label for="tags">Tagovi (odvojeni zarezom)</label>
                        <input type="text" name="tags" id="tags" value="<?= htmlspecialchars($tags_string, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group full">
                        <label for="featured_image">Istaknuta slika</label>
                        <input type="file" name="featured_image" id="featured_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                        <?php if (!empty($post['featured_image'])): ?>
                            <p class="muted">Trenutna slika:</p>
                            <img src="<?= htmlspecialchars($post['featured_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Istaknuta slika" style="max-width:240px; border-radius:8px; border:1px solid #334155;">
                        <?php endif; ?>
                    </div>

                    <div class="form-group full">
                        <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
                    </div>
                </form>
            </div>
        </section>
            </section>
    </main>
</body>

</html>

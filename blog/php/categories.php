<?php
session_start();
require_once 'Database.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->connect();

function slugify(string $text): string
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    return trim($text, '-') ?: 'kategorija';
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_category'])) {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    if ($name === '') {
        $error = 'Naziv kategorije je obavezan.';
    } else {
        $slug = $slug !== '' ? slugify($slug) : slugify($name);
        $stmt = $db->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $slug);
        if ($stmt->execute()) {
            $message = 'Kategorija je uspešno dodata.';
        } else {
            $error = 'Greška: ' . $stmt->error;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if ($id <= 0 || $name === '') {
        $error = 'Neispravni podaci za izmenu kategorije.';
    } else {
        $slug = $slug !== '' ? slugify($slug) : slugify($name);
        $stmt = $db->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?');
        $stmt->bind_param('ssi', $name, $slug, $id);
        if ($stmt->execute()) {
            $message = 'Kategorija je uspešno izmenjena.';
        } else {
            $error = 'Greška: ' . $stmt->error;
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id > 0) {
        $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $message = 'Kategorija je obrisana.';
        } else {
            $error = 'Greška: ' . $stmt->error;
        }
    }
}

$categories = $db->query('SELECT id, name, slug FROM categories ORDER BY name ASC');
$editCategory = null;

if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    if ($editId > 0) {
        $stmt = $db->prepare('SELECT id, name, slug FROM categories WHERE id = ?');
        $stmt->bind_param('i', $editId);
        $stmt->execute();
        $editCategory = $stmt->get_result()->fetch_assoc();
    }
}

function esc(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorije | Akcent Blog Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <main class="admin-wrap">
        <section class="topbar">
            <div>
                <h1>Kategorije</h1>
                <p class="muted">Upravljanje kategorijama za blog postove.</p>
            </div>
            <div style="display:flex; gap:8px;">
                <a class="btn btn-secondary" href="dashboard.php">← Dashboard</a>
                <a class="btn btn-primary" href="create-post.php">+ Novi post</a>
            </div>
        </section>

        <?php if ($message): ?><div class="alert alert-success"><?= esc($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= esc($error); ?></div><?php endif; ?>

        <section class="section">
            <div class="section-header"><h2>Dodaj novu kategoriju</h2></div>
            <div style="padding:14px;">
                <form method="POST" class="form-grid">
                    <input type="hidden" name="new_category" value="1">
                    <div class="form-group">
                        <label for="name">Naziv</label>
                        <input id="name" type="text" name="name" placeholder="npr. Moderne kuhinje" required>
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug (opciono)</label>
                        <input id="slug" type="text" name="slug" placeholder="npr. moderne-kuhinje">
                    </div>
                    <div class="form-group full">
                        <button class="btn btn-primary" type="submit">Sačuvaj kategoriju</button>
                    </div>
                </form>
            </div>
        </section>

        <?php if ($editCategory): ?>
            <section class="section">
                <div class="section-header"><h2>Izmena kategorije #<?= (int) $editCategory['id']; ?></h2></div>
                <div style="padding:14px;">
                    <form method="POST" class="form-grid">
                        <input type="hidden" name="edit_category" value="1">
                        <input type="hidden" name="id" value="<?= (int) $editCategory['id']; ?>">
                        <div class="form-group">
                            <label for="edit_name">Naziv</label>
                            <input id="edit_name" type="text" name="name" value="<?= esc($editCategory['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_slug">Slug</label>
                            <input id="edit_slug" type="text" name="slug" value="<?= esc($editCategory['slug']); ?>" required>
                        </div>
                        <div class="form-group full">
                            <button class="btn btn-info" type="submit">Sačuvaj izmene</button>
                        </div>
                    </form>
                </div>
            </section>
        <?php endif; ?>

        <section class="section">
            <div class="section-header"><h2>Lista kategorija</h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>ID</th><th>Naziv</th><th>Slug</th><th>Akcije</th></tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $cat['id']; ?></td>
                                <td><?= esc($cat['name']); ?></td>
                                <td><?= esc($cat['slug']); ?></td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="categories.php?edit=<?= (int) $cat['id']; ?>">Izmeni</a>
                                    <a class="btn btn-danger btn-sm" href="categories.php?delete=<?= (int) $cat['id']; ?>" onclick="return confirm('Obrisati kategoriju?');">Obriši</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>

<?php
require_once 'Database.php';
require_once 'config.php';
require_once 'admin_bootstrap.php';

$database = new Database();
$db = $database->connect();

$usersCount = (int) ($db->query('SELECT COUNT(*) AS c FROM users')->fetch_assoc()['c'] ?? 0);
$postsCount = (int) ($db->query('SELECT COUNT(*) AS c FROM posts')->fetch_assoc()['c'] ?? 0);
$publishedCount = (int) ($db->query("SELECT COUNT(*) AS c FROM posts WHERE status='published'")->fetch_assoc()['c'] ?? 0);
$commentsCount = (int) ($db->query('SELECT COUNT(*) AS c FROM comments')->fetch_assoc()['c'] ?? 0);
$latestPostId = (int) ($db->query('SELECT id FROM posts ORDER BY id DESC LIMIT 1')->fetch_assoc()['id'] ?? 0);

$users = $db->query('SELECT id, username, email, role, created_at FROM users ORDER BY id DESC LIMIT 12');
$posts = $db->query("SELECT posts.id, posts.title, posts.slug, posts.status, posts.created_at, users.username AS creator
                    FROM posts JOIN users ON posts.user_id = users.id
                    ORDER BY posts.id DESC LIMIT 20");

$current = 'dashboard';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Akcent Admin</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css">
</head>
<body>
    <main class="admin-shell">
        <?php include 'admin_sidebar.php'; ?>
        <section class="admin-content">
            <section class="topbar">
                <div>
                    <h1>Dashboard</h1>
                    <p class="muted">Dobrodošao, <?= admin_esc($_SESSION['username']); ?>. Sve ključne stavke su na jednom mestu.</p>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn btn-primary" href="create-post.php">+ Novi post</a>
                    <a class="btn btn-info" href="media.php">Media manager</a>
                    <a class="btn btn-secondary" href="projects.php">Projekti</a>
                </div>
            </section>

            <?php if (!empty($_GET['message'])): ?><div class="alert alert-success"><?= admin_esc($_GET['message']); ?></div><?php endif; ?>
            <?php if (!empty($_GET['error'])): ?><div class="alert alert-danger"><?= admin_esc($_GET['error']); ?></div><?php endif; ?>

            <section class="cards">
                <article class="card"><h3>Korisnici</h3><div class="value"><?= $usersCount; ?></div></article>
                <article class="card"><h3>Svi postovi</h3><div class="value"><?= $postsCount; ?></div></article>
                <article class="card"><h3>Objavljeno</h3><div class="value"><?= $publishedCount; ?></div></article>
                <article class="card"><h3>Komentari</h3><div class="value"><?= $commentsCount; ?></div></article>
            </section>

            <section class="quick-actions">
                <a class="btn btn-secondary" href="categories.php">Uredi kategorije</a>
                <?php if ($latestPostId > 0): ?><a class="btn btn-secondary" href="edit_post.php?post_id=<?= $latestPostId; ?>">Poslednji post</a><?php endif; ?>
                <a class="btn btn-secondary" href="../" target="_blank">Otvori blog</a>
                <a class="btn btn-secondary" href="<?= admin_esc(getBlogBasePath()); ?>/admin" target="_blank">Admin URL</a>
                <a class="btn btn-secondary" href="<?= admin_esc(getSiteBaseUrl()); ?>/nasi-projekti.php" target="_blank">Stranica Naši projekti</a>
            </section>

            <section class="section">
                <div class="section-header"><h2>Poslednji korisnici</h2></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>ID</th><th>Korisnik</th><th>Email</th><th>Rola</th><th>Datum</th></tr></thead>
                        <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int)$u['id']; ?></td><td><?= admin_esc($u['username']); ?></td><td><?= admin_esc($u['email']); ?></td><td><?= admin_esc($u['role']); ?></td><td><?= admin_esc($u['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="section">
                <div class="section-header"><h2>Poslednji postovi</h2></div>
                <div class="table-wrap">
                    <table class="table">
                        <thead><tr><th>ID</th><th>Naslov</th><th>Status</th><th>Kreator</th><th>Akcije</th></tr></thead>
                        <tbody>
                        <?php while ($p = $posts->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int)$p['id']; ?></td>
                                <td><?= admin_esc($p['title']); ?></td>
                                <td><span class="badge <?= $p['status'] === 'published' ? 'badge-published' : 'badge-draft'; ?>"><?= admin_esc($p['status']); ?></span></td>
                                <td><?= admin_esc($p['creator']); ?></td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="edit_post.php?post_id=<?= (int)$p['id']; ?>">Izmeni</a>
                                    <a class="btn btn-secondary btn-sm" target="_blank" href="preview.php?post_id=<?= (int)$p['id']; ?>">Preview</a>
                                    <a class="btn btn-danger btn-sm" href="delete_post.php?id=<?= (int)$p['id']; ?>" onclick="return confirm('Obrisati post?');">Obriši</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>
</body>
</html>

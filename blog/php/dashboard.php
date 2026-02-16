<?php
require_once 'Database.php';
require_once 'config.php';

session_start();

$inactive = 21600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    die('Pristup odbijen! Nemate administratorske privilegije.');
}

$database = new Database();
$db = $database->connect();

$usersCount = (int) ($db->query('SELECT COUNT(*) AS c FROM users')->fetch_assoc()['c'] ?? 0);
$postsCount = (int) ($db->query('SELECT COUNT(*) AS c FROM posts')->fetch_assoc()['c'] ?? 0);
$publishedCount = (int) ($db->query("SELECT COUNT(*) AS c FROM posts WHERE status='published'")->fetch_assoc()['c'] ?? 0);
$commentsCount = (int) ($db->query('SELECT COUNT(*) AS c FROM comments')->fetch_assoc()['c'] ?? 0);

$users = $db->query('SELECT id, username, email, role, created_at FROM users ORDER BY id DESC LIMIT 20');
$posts = $db->query("SELECT posts.id, posts.title, posts.slug, posts.status, posts.created_at, users.username AS creator
                    FROM posts
                    JOIN users ON posts.user_id = users.id
                    ORDER BY posts.id DESC
                    LIMIT 30");
$comments = $db->query("SELECT c.id, c.content, c.status, c.created_at, u.username AS user, p.title AS post
                       FROM comments c
                       JOIN users u ON c.user_id = u.id
                       JOIN posts p ON c.post_id = p.id
                       ORDER BY c.id DESC
                       LIMIT 30");

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
    <title>Admin Dashboard | Akcent Blog</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <main class="admin-wrap">
        <section class="topbar">
            <div>
                <h1>Admin Dashboard</h1>
                <p class="muted">Dobrodošao, <?= esc($_SESSION['username']); ?>. Upravljaj blogom brzo i pregledno.</p>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a class="btn btn-info" href="create-post.php">+ Novi post</a>
                <a class="btn btn-secondary" href="categories.php">Kategorije</a>
                <a class="btn btn-danger" href="logout.php">Odjavi se</a>
            </div>
        </section>

        <?php if (!empty($_GET['message'])): ?>
            <div class="alert alert-success"><?= esc($_GET['message']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= esc($_GET['error']); ?></div>
        <?php endif; ?>

        <section class="cards">
            <article class="card"><h3>Korisnici</h3><div class="value"><?= $usersCount; ?></div></article>
            <article class="card"><h3>Ukupno postova</h3><div class="value"><?= $postsCount; ?></div></article>
            <article class="card"><h3>Objavljeno</h3><div class="value"><?= $publishedCount; ?></div></article>
            <article class="card"><h3>Komentari</h3><div class="value"><?= $commentsCount; ?></div></article>
        </section>

        <section class="quick-actions">
            <a class="btn btn-primary" href="create-post.php">Kreiraj post</a>
            <a class="btn btn-secondary" href="categories.php">Uredi kategorije</a>
            <a class="btn btn-secondary" href="../" target="_blank" rel="noopener">Otvori blog</a>
        </section>

        <section class="section" id="users">
            <div class="section-header">
                <h2>Korisnici</h2>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Korisničko ime</th><th>Email</th><th>Rola</th><th>Datum registracije</th><th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $u['id']; ?></td>
                                <td><?= esc($u['username']); ?></td>
                                <td><?= esc($u['email']); ?></td>
                                <td><?= esc($u['role']); ?></td>
                                <td><?= esc($u['created_at']); ?></td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="edit_user.php?id=<?= (int) $u['id']; ?>">Izmeni</a>
                                    <a class="btn btn-danger btn-sm" href="delete_user.php?id=<?= (int) $u['id']; ?>" onclick="return confirm('Obrisati korisnika?');">Obriši</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section" id="posts">
            <div class="section-header">
                <h2>Postovi</h2>
                <a class="btn btn-primary btn-sm" href="create-post.php">+ Dodaj post</a>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Naslov</th><th>Slug</th><th>Status</th><th>Kreator</th><th>Datum</th><th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $posts->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $p['id']; ?></td>
                                <td><?= esc($p['title']); ?></td>
                                <td><?= esc($p['slug']); ?></td>
                                <td>
                                    <span class="badge <?= $p['status'] === 'published' ? 'badge-published' : 'badge-draft'; ?>">
                                        <?= esc($p['status']); ?>
                                    </span>
                                </td>
                                <td><?= esc($p['creator']); ?></td>
                                <td><?= esc($p['created_at']); ?></td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="edit_post.php?post_id=<?= (int) $p['id']; ?>">Izmeni</a>
                                    <a class="btn btn-danger btn-sm" href="delete_post.php?id=<?= (int) $p['id']; ?>" onclick="return confirm('Obrisati post?');">Obriši</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section" id="comments">
            <div class="section-header">
                <h2>Komentari</h2>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Korisnik</th><th>Post</th><th>Sadržaj</th><th>Status</th><th>Datum</th><th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($c = $comments->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $c['id']; ?></td>
                                <td><?= esc($c['user']); ?></td>
                                <td><?= esc($c['post']); ?></td>
                                <td><?= esc(strlen($c['content']) > 120 ? substr($c['content'], 0, 117) . '...' : $c['content']); ?></td>
                                <td><?= esc($c['status']); ?></td>
                                <td><?= esc($c['created_at']); ?></td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="approve_comment.php?id=<?= (int) $c['id']; ?>">Odobri</a>
                                    <a class="btn btn-warning btn-sm" href="reject_comment.php?id=<?= (int) $c['id']; ?>">Odbij</a>
                                    <a class="btn btn-danger btn-sm" href="delete_comment.php?id=<?= (int) $c['id']; ?>" onclick="return confirm('Obrisati komentar?');">Obriši</a>
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

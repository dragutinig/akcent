<?php
require_once __DIR__ . '/blog/php/config.php';

$projectsFile = __DIR__ . '/blog/data/projects.json';
$commentsFile = __DIR__ . '/blog/data/project_comments.json';

$projects = file_exists($projectsFile) ? json_decode(file_get_contents($projectsFile), true) : [];
if (!is_array($projects)) {
    $projects = [];
}

$comments = file_exists($commentsFile) ? json_decode(file_get_contents($commentsFile), true) : [];
if (!is_array($comments)) {
    $comments = [];
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    $projectId = (int) $_POST['project_id'];
    $name = trim($_POST['name'] ?? '');
    $text = trim($_POST['comment'] ?? '');
    $website = trim($_POST['website'] ?? '');

    if ($website !== '') {
        $flash = 'Komentar nije sačuvan.';
    } elseif ($projectId <= 0 || $name === '' || $text === '') {
        $flash = 'Popuni ime i komentar.';
    } else {
        $key = 'project_comment_' . $projectId;
        $last = isset($_SESSION[$key]) ? (int) $_SESSION[$key] : 0;
        if (time() - $last < 20) {
            $flash = 'Sačekaj malo pre sledećeg komentara.';
        } else {
            if (!isset($comments[$projectId]) || !is_array($comments[$projectId])) {
                $comments[$projectId] = [];
            }
            $comments[$projectId][] = [
                'name' => mb_substr($name, 0, 80),
                'comment' => mb_substr($text, 0, 800),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $_SESSION[$key] = time();
            $flash = 'Komentar je sačuvan.';
        }
    }
}

$published = array_values(array_filter($projects, function ($p) {
    return isset($p['status']) && $p['status'] === 'published';
}));

usort($published, function ($a, $b) {
    return strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? ''));
});
?>
<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Naši projekti | Akcent</title>
    <meta name="description" content="Pregled gotovih projekata sa 3D prikazom, opisom realizacije i linkom ka detaljnom blog postu.">
    <link rel="canonical" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/nasi-projekti.php">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f8; color:#1f2937; }
        .project-card { background:#fff; border-radius:14px; padding:20px; margin-bottom:18px; box-shadow:0 10px 30px rgba(17,24,39,.08); }
        .meta { color:#6b7280; font-size:14px; }
        .gallery { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
        .gallery img { width:140px; height:95px; object-fit:cover; border-radius:8px; }
        .comment { border-top:1px solid #e5e7eb; padding-top:10px; margin-top:10px; }
    </style>
</head>
<body>
<?php include __DIR__ . '/komponente/header.php'; ?>
<div class="container py-5">
    <h1 class="mb-3">Naši gotovi projekti</h1>
    <p class="mb-4">Ovde su završeni projekti sa kratkim opisom, 3D modelom i vezom ka detaljnom blog tekstu.</p>

    <?php if ($flash !== ''): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if (empty($published)): ?>
        <p>Trenutno nema objavljenih projekata.</p>
    <?php endif; ?>

    <?php foreach ($published as $p):
        $id = (int) ($p['id'] ?? 0);
        $modelUrlRaw = trim((string) ($p['model_url'] ?? ''));
        $modelUrl = $modelUrlRaw === '' ? '' : (strpos($modelUrlRaw, 'http') === 0 ? $modelUrlRaw : getSiteBaseUrl() . '/' . ltrim($modelUrlRaw, '/'));
        $blogUrlRaw = trim((string) ($p['blog_url'] ?? ''));
        $blogUrl = $blogUrlRaw === '' ? '' : (strpos($blogUrlRaw, 'http') === 0 ? $blogUrlRaw : getSiteBaseUrl() . '/' . ltrim($blogUrlRaw, '/'));
        $imgs = array_filter(array_map('trim', explode(',', (string) ($p['real_images'] ?? ''))));
        $projectComments = isset($comments[$id]) && is_array($comments[$id]) ? $comments[$id] : [];
    ?>
    <article class="project-card">
        <h2><?php echo htmlspecialchars($p['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>
        <p class="meta">Objavljeno: <?php echo htmlspecialchars($p['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php echo nl2br(htmlspecialchars($p['description'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>

        <div class="d-flex flex-wrap gap-2 mb-2">
            <?php if ($modelUrl !== ''): ?><a class="btn btn-dark" target="_blank" rel="noopener" href="<?php echo htmlspecialchars($modelUrl, ENT_QUOTES, 'UTF-8'); ?>">Otvori 3D projekat</a><?php endif; ?>
            <?php if ($blogUrl !== ''): ?><a class="btn btn-outline-primary" href="<?php echo htmlspecialchars($blogUrl, ENT_QUOTES, 'UTF-8'); ?>">Pročitaj ceo projekat na blogu</a><?php endif; ?>
        </div>

        <?php if (!empty($imgs)): ?>
        <div class="gallery">
            <?php foreach ($imgs as $img):
                $src = strpos($img, 'http') === 0 ? $img : getSiteBaseUrl() . '/' . ltrim($img, '/');
            ?>
                <img src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p['title'] ?? 'Projekat', ENT_QUOTES, 'UTF-8'); ?>">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="comment">
            <h3 class="h5">Komentari</h3>
            <?php foreach (array_slice(array_reverse($projectComments), 0, 5) as $c): ?>
                <p><strong><?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?></strong> <span class="meta">(<?php echo htmlspecialchars($c['created_at'], ENT_QUOTES, 'UTF-8'); ?>)</span><br><?php echo nl2br(htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8')); ?></p>
            <?php endforeach; ?>
            <form method="post" class="mt-3">
                <input type="hidden" name="project_id" value="<?php echo $id; ?>">
                <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;">
                <div class="mb-2"><input class="form-control" type="text" name="name" placeholder="Ime" required></div>
                <div class="mb-2"><textarea class="form-control" name="comment" rows="3" placeholder="Tvoj komentar" required></textarea></div>
                <button class="btn btn-primary" type="submit">Pošalji komentar</button>
            </form>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/komponente/footer.php'; ?>
</body>
</html>

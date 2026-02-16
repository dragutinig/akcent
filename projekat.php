<?php
require_once __DIR__ . '/blog/php/config.php';
require_once __DIR__ . '/blog/php/Database.php';
require_once __DIR__ . '/blog/php/ProjectRepository.php';

start_secure_session();
$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();

$slug = trim($_GET['slug'] ?? '');
$project = $slug !== '' ? $repo->getProjectBySlug($slug) : null;
if (!$project || $project['status'] !== 'published') {
    http_response_code(404);
    echo 'Projekat nije pronađen.';
    exit();
}

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $website = trim($_POST['website'] ?? '');

    if ($website !== '') {
        $flash = 'Komentar nije sačuvan.';
    } elseif ($name === '' || $comment === '') {
        $flash = 'Popuni ime i komentar.';
    } else {
        $key = 'project_comment_' . $project['id'];
        $last = (int) ($_SESSION[$key] ?? 0);
        if (time() - $last < 20) {
            $flash = 'Sačekaj malo pre narednog komentara.';
        } else {
            $repo->addComment([
                'project_id' => (int) $project['id'],
                'author_name' => mb_substr($name, 0, 120),
                'author_email' => mb_substr($email, 0, 190),
                'comment_text' => mb_substr($comment, 0, 1500),
                'status' => 'approved',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ]);
            $_SESSION[$key] = time();
            $flash = 'Komentar je objavljen.';
        }
    }
}

$comments = $repo->listApprovedComments((int) $project['id'], 20);
$projectModels = $project['models'] ?? [];
if (empty($projectModels) && !empty($project['model_path'])) {
    $projectModels[] = [
        'model_label' => '3D model',
        'model_path' => $project['model_path'],
    ];
}

$blogUrl = trim((string) ($project['blog_post_url'] ?? ''));
$blogUrl = $blogUrl !== '' ? (strpos($blogUrl, 'http') === 0 ? $blogUrl : buildPublicUrlFromPath($blogUrl)) : '';
$metaTitle = trim((string) ($project['meta_title'] ?? '')) ?: $project['title'];
$metaDesc = trim((string) ($project['meta_description'] ?? ''));
?>
<!doctype html>
<html class="no-js" lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDesc, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/projekat.php?slug=<?php echo urlencode($project['slug']); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/projekti.css">
</head>
<body class="projects-page">
<?php include __DIR__ . '/komponente/nav.php'; ?>
<div style="height:50px;"></div>
<main class="projects-shell">
    <p><a href="nasi-projekti.php">← Svi projekti</a></p>
    <article class="project-detail">
        <h1><?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="project-date">Datum: <?php echo htmlspecialchars(date('d.m.Y', strtotime($project['created_at']))); ?></p>
        <?php if (!empty($project['excerpt'])): ?><p><?php echo htmlspecialchars($project['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <?php foreach ($projectModels as $index => $model): ?>
                <?php
                $modelPath = trim((string) ($model['model_path'] ?? ''));
                if ($modelPath === '') {
                    continue;
                }
                $modelUrl = strpos($modelPath, 'http') === 0 ? $modelPath : buildPublicUrlFromPath($modelPath);
                $modelLabel = trim((string) ($model['model_label'] ?? '')) ?: ('3D model ' . ($index + 1));
                ?>
                <a class="btn btn-dark" target="_blank" rel="noopener" href="<?php echo htmlspecialchars($modelUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($modelLabel, ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
            <?php if ($blogUrl !== ''): ?><a class="btn btn-outline-secondary" href="<?php echo htmlspecialchars($blogUrl, ENT_QUOTES, 'UTF-8'); ?>">Povezani blog post</a><?php endif; ?>
        </div>

        <div><?php echo $project['content']; ?></div>

        <?php if (!empty($project['images'])): ?>
        <section class="project-gallery">
            <?php foreach ($project['images'] as $img): $src = buildPublicUrlFromPath($img['image_path']); ?>
                <img src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($img['alt_text'] ?: $project['title']), ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars((string) ($img['title_text'] ?: $project['title']), ENT_QUOTES, 'UTF-8'); ?>">
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
    </article>

    <section class="comments-wrap">
        <h2>Komentari</h2>
        <?php if ($flash): ?><div class="alert alert-info"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <?php foreach ($comments as $c): ?>
            <div class="comment-item">
                <strong><?php echo htmlspecialchars($c['author_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <small class="project-date"><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($c['created_at']))); ?></small>
                <p class="mb-0"><?php echo nl2br(htmlspecialchars($c['comment_text'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
        <?php endforeach; ?>

        <form method="post" class="project-detail">
            <input type="text" name="website" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px;">
            <div class="row g-2">
                <div class="col-md-6"><input class="form-control" name="name" placeholder="Ime" required></div>
                <div class="col-md-6"><input class="form-control" type="email" name="email" placeholder="Email (opciono)"></div>
                <div class="col-12"><textarea class="form-control" name="comment" rows="4" placeholder="Komentar" required></textarea></div>
                <div class="col-12"><button class="btn btn-primary" type="submit">Pošalji komentar</button></div>
            </div>
        </form>
    </section>
</main>
<?php include __DIR__ . '/komponente/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/vendor/modernizr-3.8.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
</body>
</html>

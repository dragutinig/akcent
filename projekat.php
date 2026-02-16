<?php
require_once __DIR__ . '/blog/php/config.php';
require_once __DIR__ . '/blog/php/Database.php';
require_once __DIR__ . '/blog/php/ProjectRepository.php';

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

function resolveProjectAssetUrl(string $rawPath): string
{
    $rawPath = trim($rawPath);
    if ($rawPath === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $rawPath)) {
        return $rawPath;
    }

    $normalized = str_replace('\\', '/', $rawPath);

    if (preg_match('#^[A-Za-z]:/#', $normalized)) {
        $file = basename($normalized);
        if ($file !== '') {
            return getSiteBaseUrl() . '/blog/uploads/projects/' . rawurlencode($file);
        }
    }

    if (strpos($normalized, 'blog/') === 0) {
        return getSiteBaseUrl() . '/' . str_replace(' ', '%20', ltrim($normalized, '/'));
    }

    if (strpos($normalized, 'uploads/') === 0 || strpos($normalized, '../uploads/') === 0) {
        $normalized = ltrim(str_replace('../', '', $normalized), '/');
        return getSiteBaseUrl() . '/blog/' . str_replace(' ', '%20', $normalized);
    }

    return getSiteBaseUrl() . '/' . str_replace(' ', '%20', ltrim($normalized, '/'));
}

$modelUrl = trim((string) ($project['model_path'] ?? ''));
$modelUrl = $modelUrl !== '' ? (strpos($modelUrl, 'http') === 0 ? $modelUrl : getSiteBaseUrl() . '/' . ltrim(str_replace(' ', '%20', $modelUrl), '/')) : '';
$blogUrl = trim((string) ($project['blog_post_url'] ?? ''));
$blogUrl = $blogUrl !== '' ? (strpos($blogUrl, 'http') === 0 ? $blogUrl : getSiteBaseUrl() . '/' . ltrim($blogUrl, '/')) : '';
$metaTitle = trim((string) ($project['meta_title'] ?? '')) ?: $project['title'];
$metaDesc = trim((string) ($project['meta_description'] ?? ''));
$project3dUrl = 'projekat-3d.php?slug=' . urlencode((string) $project['slug']);
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
            <?php if ($modelUrl !== ''): ?><a class="btn btn-dark" href="<?php echo htmlspecialchars($project3dUrl, ENT_QUOTES, 'UTF-8'); ?>">Otvori 3D model</a><?php endif; ?>
            <?php if ($blogUrl !== ''): ?><a class="btn btn-outline-secondary" href="<?php echo htmlspecialchars($blogUrl, ENT_QUOTES, 'UTF-8'); ?>">Povezani blog post</a><?php endif; ?>
        </div>

        <div><?php echo $project['content']; ?></div>

        <?php if (!empty($project['images'])): ?>
        <section class="project-gallery">
            <?php foreach ($project['images'] as $img): $src = resolveProjectAssetUrl((string) $img['image_path']); ?>
                <img src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) ($img['alt_text'] ?: $project['title']), ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars((string) ($img['title_text'] ?: $project['title']), ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
    </article>
</main>
<?php include __DIR__ . '/komponente/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/vendor/modernizr-3.8.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/blog/php/config.php';
require_once __DIR__ . '/blog/php/Database.php';
require_once __DIR__ . '/blog/php/ProjectRepository.php';

$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();
$projects = $repo->listProjects('published');

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
?>
<!doctype html>
<html class="no-js" lang="sr">
<head>
    <meta charset="utf-8">
    <title>Naši projekti | Akcent Nameštaj</title>
    <meta name="description" content="Pogledajte naše gotove projekte sa fotografijama, datumom i 3D model prikazom.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/nasi-projekti.php" />
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
    <h1 class="projects-title">Naši projekti</h1>

    <section class="projects-grid">
        <?php foreach ($projects as $project):
            $cover = $project['images'][0]['image_path'] ?? '';
            $coverUrl = resolveProjectAssetUrl((string) $cover);
        ?>
        <article class="project-card">
            <?php if ($coverUrl !== ''): ?>
                <img src="<?php echo htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
            <?php endif; ?>
            <div class="project-card-content">
                <div class="project-date"><?php echo htmlspecialchars(date('d.m.Y', strtotime($project['created_at']))); ?></div>
                <h2><?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p><?php echo htmlspecialchars((string) ($project['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                <a class="btn btn-dark" href="projekat.php?slug=<?php echo urlencode($project['slug']); ?>">Otvori projekat</a>
            </div>
        </article>
        <?php endforeach; ?>
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

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
    echo '3D projekat nije pronađen.';
    exit();
}

function resolveProjectModelUrl(string $rawPath): string
{
    $rawPath = trim($rawPath);
    if ($rawPath === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $rawPath)) {
        return $rawPath;
    }

    $normalized = str_replace('\\', '/', $rawPath);

    if (strpos($normalized, 'blog/') === 0) {
        return getSiteBaseUrl() . '/' . str_replace(' ', '%20', ltrim($normalized, '/'));
    }

    if (strpos($normalized, 'project-models/') === 0) {
        return getSiteBaseUrl() . '/blog/' . str_replace(' ', '%20', $normalized);
    }

    $marker = '/blog/project-models/';
    $pos = strpos($normalized, $marker);
    if ($pos !== false) {
        $relative = substr($normalized, $pos + 1);
        return getSiteBaseUrl() . '/' . str_replace(' ', '%20', $relative);
    }

    $marker2 = 'project-models/';
    $pos2 = strpos($normalized, $marker2);
    if ($pos2 !== false) {
        $relative = substr($normalized, $pos2);
        return getSiteBaseUrl() . '/blog/' . str_replace(' ', '%20', $relative);
    }

    return getSiteBaseUrl() . '/' . ltrim(str_replace(' ', '%20', $normalized), '/');
}

$modelUrl = resolveProjectModelUrl((string) ($project['model_path'] ?? ''));
$modelError = $modelUrl === '' ? '3D model nije dodat za ovaj projekat.' : '';
?>
<!doctype html>
<html class="no-js" lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>3D model | <?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/projekti.css">

    <?php include("komponente/seo.php"); ?>

</head>
<body class="projects-page">
<?php include __DIR__ . '/komponente/nav.php'; ?>
<div style="height:50px;"></div>
<main class="project-3d-only-shell">
    <?php if ($modelUrl !== ''): ?>
        <iframe src="<?php echo htmlspecialchars($modelUrl, ENT_QUOTES, 'UTF-8'); ?>" title="3D model <?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>"></iframe>
    <?php else: ?>
        <div class="container py-4">
            <div class="alert alert-warning mb-0"><?php echo htmlspecialchars($modelError, ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    <?php endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

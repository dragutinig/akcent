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

$modelPath = trim((string) ($project['model_path'] ?? ''));
$modelUrl = '';
$modelError = '';

if ($modelPath === '') {
    $modelError = '3D model nije dodat za ovaj projekat.';
} elseif (strpos($modelPath, 'http') === 0) {
    $modelUrl = $modelPath;
} else {
    $normalizedPath = ltrim($modelPath, '/');
    $absolutePath = __DIR__ . '/' . str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);

    if (is_file($absolutePath)) {
        $modelUrl = getSiteBaseUrl() . '/' . ltrim(str_replace(' ', '%20', $modelPath), '/');
    } else {
        $modelError = '3D fajl nije pronađen na serveru.';
    }
}
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

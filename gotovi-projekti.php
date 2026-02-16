<?php
require_once __DIR__ . '/blog/php/config.php';
require_once __DIR__ . '/blog/php/Database.php';
require_once __DIR__ . '/blog/php/ProjectRepository.php';

$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();
$projects = $repo->listProjects('published');
?>
<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gotovi projekti | Akcent Nameštaj</title>
    <meta name="description" content="Pregled gotovih projekata sa 3D prikazima, fotografijama realizacije i detaljnim opisima.">
    <link rel="canonical" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/gotovi-projekti.php">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/css/style.css">
    <script src="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/js/main.js"></script>
</head>
<body>
<?php include __DIR__ . '/komponente/nav.php'; ?>
<div class="container py-5">
    <h1 class="mb-3">Gotovi projekti</h1>
    <p class="text-muted">Prikaz realizovanih projekata i 3D modela.</p>

    <div class="row g-4">
        <?php foreach ($projects as $project):
            $cover = $project['images'][0]['image_path'] ?? '';
            $coverUrl = $cover !== '' ? getSiteBaseUrl() . '/' . ltrim($cover, '/') : '';
        ?>
        <div class="col-lg-4 col-md-6">
            <article class="card h-100 shadow-sm">
                <?php if ($coverUrl !== ''): ?>
                    <img src="<?php echo htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>" style="height:220px;object-fit:cover;">
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted"><?php echo htmlspecialchars(date('d.m.Y', strtotime($project['created_at']))); ?></small>
                    <h2 class="h5 mt-1"><?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars((string) ($project['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <a class="btn btn-dark mt-auto" href="projekat.php?slug=<?php echo urlencode($project['slug']); ?>">Detalji projekta</a>
                </div>
            </article>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/komponente/footer.php'; ?>
</body>
</html>

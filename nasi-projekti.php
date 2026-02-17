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
    <p class="projects-subtitle">Izaberite projekat i pogledajte fotografije, opis i 3D prikaz rešenja.</p>

    <section class="projects-grid">
        <?php foreach ($projects as $project):
            $cover = $project['images'][0]['image_path'] ?? '';
            $coverUrl = $cover !== '' ? buildPublicUrlFromPath($cover) : '';
            $excerpt = trim((string) ($project['excerpt'] ?? ''));
            if ($excerpt === '') {
                $excerpt = 'Pogledajte detalje projekta, fotografije i dostupne 3D modele.';
            }
        ?>
        <article class="project-card post-card-like">
            <a class="project-card-link" href="projekat.php?slug=<?php echo urlencode($project['slug']); ?>">
                <div class="project-thumb-wrap">
                    <?php if ($coverUrl !== ''): ?>
                        <img src="<?php echo htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php else: ?>
                        <img src="img/kuhinjeGalerija/kuhinje-po-meri-beograd-0.webp" alt="<?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php endif; ?>
                    <div class="project-badge-row"><span class="project-badge-item">Projekat</span></div>
                </div>
                <div class="project-card-content">
                    <div class="project-date"><?php echo htmlspecialchars(date('d.m.Y', strtotime($project['created_at']))); ?></div>
                    <h2><?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><?php echo htmlspecialchars(mb_substr($excerpt, 0, 180), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </a>
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

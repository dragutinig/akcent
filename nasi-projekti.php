<?php
require_once __DIR__ . '/blog/php/config.php';
require_once __DIR__ . '/blog/php/Database.php';
require_once __DIR__ . '/blog/php/ProjectRepository.php';

$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();
$projects = $repo->listProjects('published');

$months = [];
$years = [];
foreach ($projects as $p) {
    $timestamp = strtotime((string) $p['created_at']);
    if ($timestamp === false) {
        continue;
    }
    $m = date('m', $timestamp);
    $y = date('Y', $timestamp);
    $months[$m] = date('F', mktime(0, 0, 0, (int) $m, 1));
    $years[$y] = $y;
}
krsort($years);
ksort($months);
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
    <p class="projects-subtitle">Tabela / kartice sa projektima: slika, naslov i datum.</p>

    <div class="projects-filter">
        <select id="project-month" class="form-select">
            <option value="">Svi meseci</option>
            <?php foreach ($months as $monthNumber => $monthName): ?>
                <option value="<?php echo htmlspecialchars($monthNumber, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($monthName, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
        <select id="project-year" class="form-select">
            <option value="">Sve godine</option>
            <?php foreach ($years as $year): ?>
                <option value="<?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($year, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <section class="projects-grid">
        <?php foreach ($projects as $project):
            $cover = $project['images'][0]['image_path'] ?? '';
            $coverUrl = $cover !== '' ? getSiteBaseUrl() . '/' . ltrim(str_replace(' ', '%20', $cover), '/') : '';
            $ts = strtotime((string) $project['created_at']);
        ?>
        <article class="project-card" data-month="<?php echo htmlspecialchars($ts ? date('m', $ts) : '', ENT_QUOTES, 'UTF-8'); ?>" data-year="<?php echo htmlspecialchars($ts ? date('Y', $ts) : '', ENT_QUOTES, 'UTF-8'); ?>">
            <?php if ($coverUrl !== ''): ?>
                <img src="<?php echo htmlspecialchars($coverUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8'); ?>">
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const month = document.getElementById('project-month');
    const year = document.getElementById('project-year');
    const cards = document.querySelectorAll('.project-card');

    function filterProjects() {
        cards.forEach(function (card) {
            const monthOk = !month.value || card.dataset.month === month.value;
            const yearOk = !year.value || card.dataset.year === year.value;
            card.style.display = monthOk && yearOk ? '' : 'none';
        });
    }

    month.addEventListener('change', filterProjects);
    year.addEventListener('change', filterProjects);
});
</script>
</body>
</html>

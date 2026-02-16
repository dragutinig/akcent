<?php
$current = $current ?? 'dashboard';
$base = getBlogBasePath() . '/php';
$menu = [
    'dashboard' => ['label' => 'Dashboard', 'href' => $base . '/dashboard.php'],
    'create' => ['label' => 'Novi post', 'href' => $base . '/create-post.php'],
    'categories' => ['label' => 'Kategorije', 'href' => $base . '/categories.php'],
    'media' => ['label' => 'Media manager', 'href' => $base . '/media.php'],
    'projects' => ['label' => 'Projekti', 'href' => $base . '/projects.php'],
];
?>
<aside class="admin-sidebar">
    <div class="brand">Akcent Admin</div>
    <nav>
        <?php foreach ($menu as $key => $item): ?>
            <a class="sidebar-link <?= $current === $key ? 'active' : ''; ?>" href="<?= admin_esc($item['href']); ?>">
                <?= admin_esc($item['label']); ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <a class="btn btn-danger" href="<?= admin_esc($base . '/logout.php'); ?>" style="margin-top:auto;">Odjavi se</a>
</aside>

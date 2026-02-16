<?php
$current = $current ?? 'dashboard';
$menu = [
    'dashboard' => ['label' => 'Dashboard', 'href' => 'dashboard.php'],
    'create' => ['label' => 'Novi post', 'href' => 'create-post.php'],
    'categories' => ['label' => 'Kategorije', 'href' => 'categories.php'],
    'media' => ['label' => 'Media manager', 'href' => 'media.php'],
    'projects' => ['label' => 'Projekti', 'href' => 'projects.php'],
];
?>
<aside class="admin-sidebar">
    <div class="brand">Akcent Admin</div>
    <nav>
        <?php foreach ($menu as $key => $item): ?>
            <a class="sidebar-link <?= $current === $key ? 'active' : ''; ?>" href="<?= $item['href']; ?>">
                <?= admin_esc($item['label']); ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <a class="btn btn-danger" href="logout.php" style="margin-top:auto;">Odjavi se</a>
</aside>

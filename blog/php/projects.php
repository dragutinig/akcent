<?php
require_once 'config.php';
require_once 'admin_bootstrap.php';

$dataFile = __DIR__ . '/../data/projects.json';
if (!is_dir(dirname($dataFile))) {
    mkdir(dirname($dataFile), 0777, true);
}
$projects = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
if (!is_array($projects)) {
    $projects = [];
}

function pslug(string $text): string
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    return trim($text, '-') ?: 'projekat';
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        $slug = pslug($_POST['slug'] ?? $title);
        $projects[] = [
            'id' => time(),
            'title' => $title,
            'slug' => $slug,
            'status' => $_POST['status'] ?? 'draft',
            'model_url' => trim($_POST['model_url'] ?? ''),
            'real_images' => trim($_POST['real_images'] ?? ''),
            'blog_url' => trim($_POST['blog_url'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $message = 'Projekat je dodat.';
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $projects = array_values(array_filter($projects, fn($p) => (int)($p['id'] ?? 0) !== $id));
        $message = 'Projekat je obrisan.';
    }

    file_put_contents($dataFile, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$current = 'projects';
?>
<!DOCTYPE html><html lang="sr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Projekti</title><link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css"></head>
<body><main class="admin-shell"><?php include 'admin_sidebar.php'; ?><section class="admin-content">
<section class="topbar"><div><h1>Projekti (3D + Realizacija + Blog)</h1><p class="muted">Kreiraj završene projekte: 3D model URL, realne slike i povezani blog post.</p></div></section>
<?php if ($message): ?><div class="alert alert-success"><?= admin_esc($message); ?></div><?php endif; ?>
<section class="section"><div class="section-header"><h2>Novi projekat</h2></div><div style="padding:14px;">
<form method="POST" class="form-grid">
<input type="hidden" name="action" value="add">
<div class="form-group"><label>Naslov</label><input name="title" required></div>
<div class="form-group"><label>Slug</label><input name="slug" placeholder="opciono"></div>
<div class="form-group"><label>Status</label><select name="status"><option value="draft">Draft</option><option value="published">Published</option></select></div>
<div class="form-group"><label>URL 3D modela</label><input name="model_url" placeholder="https://..."></div>
<div class="form-group full"><label>Realne slike (putanje, odvojene zarezom)</label><input name="real_images" placeholder="gallery/projekat1.webp, img/projekat2.webp"></div>
<div class="form-group"><label>Blog post URL</label><input name="blog_url" placeholder="/blog/kategorija/slug"></div>
<div class="form-group full"><label>Opis</label><textarea name="description"></textarea></div>
<div class="form-group full"><button class="btn btn-primary" type="submit">Sačuvaj projekat</button></div>
</form></div></section>

<section class="section"><div class="section-header"><h2>Lista projekata</h2></div><div class="table-wrap"><table class="table"><thead><tr><th>Naslov</th><th>Status</th><th>3D model</th><th>Slike</th><th>Blog</th><th>Akcije</th></tr></thead><tbody>
<?php foreach (array_reverse($projects) as $p): ?>
<tr>
<td><strong><?= admin_esc($p['title'] ?? ''); ?></strong><br><span class="muted">slug: <?= admin_esc($p['slug'] ?? ''); ?></span></td>
<td><span class="badge <?= ($p['status'] ?? '') === 'published' ? 'badge-published' : 'badge-draft'; ?>"><?= admin_esc($p['status'] ?? 'draft'); ?></span></td>
<td><?php if (!empty($p['model_url'])): ?><a href="<?= admin_esc($p['model_url']); ?>" target="_blank">Model</a><?php endif; ?></td>
<td><?= admin_esc($p['real_images'] ?? ''); ?></td>
<td><?= admin_esc($p['blog_url'] ?? ''); ?></td>
<td><form method="POST" onsubmit="return confirm('Obrisati projekat?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)($p['id'] ?? 0); ?>"><button class="btn btn-danger btn-sm" type="submit">Obriši</button></form></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></section>
</section></main></body></html>

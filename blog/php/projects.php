<?php
require_once 'config.php';
require_once 'admin_bootstrap.php';

$dataFile = __DIR__ . '/../data/projects.json';
$modelStorageAbs = realpath(__DIR__ . '/..') . '/project-models';
$modelStorageRel = 'blog/project-models';

if (!is_dir(dirname($dataFile))) {
    mkdir(dirname($dataFile), 0777, true);
}
if (!is_dir($modelStorageAbs)) {
    mkdir($modelStorageAbs, 0777, true);
}

$projects = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
if (!is_array($projects)) {
    $projects = [];
}

function pslug($text)
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    return trim($text, '-') ?: 'projekat';
}

function find_model_entry_html($directoryAbs, $folderName)
{
    $candidates = [
        $directoryAbs . '/' . $folderName . '.html',
        $directoryAbs . '/index.html',
        $directoryAbs . '/model.html',
    ];

    foreach ($candidates as $file) {
        if (is_file($file)) {
            return basename($file);
        }
    }

    $files = scandir($directoryAbs);
    if (!is_array($files)) {
        return '';
    }

    foreach ($files as $file) {
        if (preg_match('/\.html?$/i', $file)) {
            return $file;
        }
    }

    return '';
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'add') {
        $title = trim(isset($_POST['title']) ? $_POST['title'] : '');
        $slug = pslug(isset($_POST['slug']) && trim($_POST['slug']) !== '' ? $_POST['slug'] : $title);

        if ($title === '') {
            $error = 'Naslov je obavezan.';
        } else {
            $modelUrl = trim(isset($_POST['model_url']) ? $_POST['model_url'] : '');

            if (isset($_FILES['model_archive']) && $_FILES['model_archive']['error'] === UPLOAD_ERR_OK) {
                if (!class_exists('ZipArchive')) {
                    $error = 'PHP ZipArchive ekstenzija nije dostupna na serveru.';
                } else {
                    $zip = new ZipArchive();
                    $tmpZip = $_FILES['model_archive']['tmp_name'];
                    if ($zip->open($tmpZip) === true) {
                        $safeFolder = $slug . '-' . time();
                        $destination = $modelStorageAbs . '/' . $safeFolder;
                        mkdir($destination, 0777, true);
                        $zip->extractTo($destination);
                        $zip->close();

                        $entry = find_model_entry_html($destination, $slug);
                        if ($entry !== '') {
                            $modelUrl = $modelStorageRel . '/' . $safeFolder . '/' . $entry;
                        } else {
                            $error = 'Arhiva je uploadovana, ali nije pronađen .html fajl za 3D pregled.';
                        }
                    } else {
                        $error = 'Neuspešno otvaranje ZIP arhive.';
                    }
                }
            }

            if ($error === '') {
                $projects[] = [
                    'id' => time(),
                    'title' => $title,
                    'slug' => $slug,
                    'status' => isset($_POST['status']) ? $_POST['status'] : 'draft',
                    'model_url' => $modelUrl,
                    'real_images' => trim(isset($_POST['real_images']) ? $_POST['real_images'] : ''),
                    'blog_url' => trim(isset($_POST['blog_url']) ? $_POST['blog_url'] : ''),
                    'description' => trim(isset($_POST['description']) ? $_POST['description'] : ''),
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $message = 'Projekat je dodat.';
            }
        }
    }

    if ($action === 'delete') {
        $id = (int) (isset($_POST['id']) ? $_POST['id'] : 0);
        $projects = array_values(array_filter($projects, function ($p) use ($id) {
            return (int) (isset($p['id']) ? $p['id'] : 0) !== $id;
        }));
        $message = 'Projekat je obrisan.';
    }

    file_put_contents($dataFile, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$current = 'projects';
?>
<!DOCTYPE html><html lang="sr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Projekti</title><link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css"></head>
<body><main class="admin-shell"><?php include 'admin_sidebar.php'; ?><section class="admin-content">
<section class="topbar"><div><h1>Projekti (3D + Realizacija + Blog)</h1><p class="muted">Sačuvaj završene projekte i poveži ih sa blog pričom.</p></div></section>
<?php if ($message): ?><div class="alert alert-success"><?= admin_esc($message); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= admin_esc($error); ?></div><?php endif; ?>
<section class="section"><div class="section-header"><h2>Novi projekat</h2></div><div style="padding:14px;">
<form method="POST" enctype="multipart/form-data" class="form-grid">
<input type="hidden" name="action" value="add">
<div class="form-group"><label>Naslov</label><input name="title" required></div>
<div class="form-group"><label>Slug</label><input name="slug" placeholder="opciono"></div>
<div class="form-group"><label>Status</label><select name="status"><option value="draft">Draft</option><option value="published">Published</option></select></div>
<div class="form-group"><label>URL 3D modela</label><input name="model_url" placeholder="npr. blog/project-models/model/index.html"></div>
<div class="form-group full"><label>Upload 3D modela (ZIP foldera)</label><input type="file" name="model_archive" accept=".zip"><small class="muted">Ako pošalješ ZIP folder (html/x3d/js/css), sistem će ga automatski raspakovati.</small></div>
<div class="form-group full"><label>Realne slike (putanje, odvojene zarezom)</label><input name="real_images" placeholder="gallery/projekat1.webp, img/projekat2.webp"></div>
<div class="form-group"><label>Blog post URL</label><input name="blog_url" placeholder="/blog/kategorija/slug"></div>
<div class="form-group full"><label>Opis</label><textarea name="description"></textarea></div>
<div class="form-group full"><button class="btn btn-primary" type="submit">Sačuvaj projekat</button></div>
</form></div></section>

<section class="section"><div class="section-header"><h2>Lista projekata</h2></div><div class="table-wrap"><table class="table"><thead><tr><th>Naslov</th><th>Status</th><th>3D model</th><th>Slike</th><th>Blog</th><th>Akcije</th></tr></thead><tbody>
<?php foreach (array_reverse($projects) as $p): ?>
<tr>
<td><strong><?= admin_esc(isset($p['title']) ? $p['title'] : ''); ?></strong><br><span class="muted">slug: <?= admin_esc(isset($p['slug']) ? $p['slug'] : ''); ?></span></td>
<td><span class="badge <?= (isset($p['status']) ? $p['status'] : '') === 'published' ? 'badge-published' : 'badge-draft'; ?>"><?= admin_esc(isset($p['status']) ? $p['status'] : 'draft'); ?></span></td>
<td><?php if (!empty($p['model_url'])): ?><a href="<?= admin_esc(strpos($p['model_url'], 'http') === 0 ? $p['model_url'] : getSiteBaseUrl() . '/' . ltrim($p['model_url'], '/')); ?>" target="_blank">Model</a><?php endif; ?></td>
<td><?= admin_esc(isset($p['real_images']) ? $p['real_images'] : ''); ?></td>
<td><?= admin_esc(isset($p['blog_url']) ? $p['blog_url'] : ''); ?></td>
<td><form method="POST" onsubmit="return confirm('Obrisati projekat?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)(isset($p['id']) ? $p['id'] : 0); ?>"><button class="btn btn-danger btn-sm" type="submit">Obriši</button></form></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></section>
</section></main></body></html>

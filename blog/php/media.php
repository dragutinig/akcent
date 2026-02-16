<?php
require_once 'admin_bootstrap.php';
require_once 'config.php';

$metaFile = __DIR__ . '/../data/image_meta.json';
if (!is_dir(dirname($metaFile))) {
    mkdir(dirname($metaFile), 0777, true);
}
$imageMeta = file_exists($metaFile) ? json_decode(file_get_contents($metaFile), true) : [];
if (!is_array($imageMeta)) {
    $imageMeta = [];
}

$allowedRoots = [
    realpath(__DIR__ . '/../../img'),
    realpath(__DIR__ . '/../../gallery'),
    realpath(__DIR__ . '/../uploads'),
];
$allowedRoots = array_filter($allowedRoots);

function rel_from_root(string $path): string {
    $root = realpath(__DIR__ . '/../..');
    return ltrim(str_replace($root, '', $path), DIRECTORY_SEPARATOR);
}

function is_allowed_path(string $path, array $roots): bool {
    $real = realpath($path);
    if ($real === false) return false;
    foreach ($roots as $root) {
        if (strpos($real, $root) === 0) return true;
    }
    return false;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $targetRel = trim($_POST['target'] ?? '');
    $targetAbs = realpath(__DIR__ . '/../../' . $targetRel);

    if ($action === 'save_meta' && $targetAbs && is_allowed_path($targetAbs, $allowedRoots)) {
        $imageMeta[$targetRel] = [
            'alt' => trim($_POST['alt'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
        ];
        file_put_contents($metaFile, json_encode($imageMeta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $message = 'Meta podaci slike su sačuvani.';
    }

    if ($action === 'delete_image' && $targetAbs && is_allowed_path($targetAbs, $allowedRoots) && is_file($targetAbs)) {
        if (unlink($targetAbs)) {
            unset($imageMeta[$targetRel]);
            file_put_contents($metaFile, json_encode($imageMeta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $message = 'Slika je obrisana.';
        } else {
            $error = 'Brisanje slike nije uspelo.';
        }
    }

    if ($action === 'replace_image' && $targetAbs && is_allowed_path($targetAbs, $allowedRoots) && isset($_FILES['replacement']) && $_FILES['replacement']['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES['replacement']['tmp_name'], $targetAbs)) {
            $message = 'Slika je uspešno zamenjena.';
        } else {
            $error = 'Zamena slike nije uspela.';
        }
    }

    if ($action === 'resize_image' && $targetAbs && is_allowed_path($targetAbs, $allowedRoots) && is_file($targetAbs)) {
        $w = (int) ($_POST['width'] ?? 0);
        $h = (int) ($_POST['height'] ?? 0);
        if ($w > 0 && $h > 0 && extension_loaded('gd')) {
            $info = getimagesize($targetAbs);
            if ($info) {
                [$ow, $oh, $type] = $info;
                $src = null;
                if ($type === IMAGETYPE_JPEG) {
                    $src = imagecreatefromjpeg($targetAbs);
                } elseif ($type === IMAGETYPE_PNG) {
                    $src = imagecreatefrompng($targetAbs);
                } elseif ($type === IMAGETYPE_WEBP && function_exists('imagecreatefromwebp')) {
                    $src = imagecreatefromwebp($targetAbs);
                }

                if ($src) {
                    $dst = imagecreatetruecolor($w, $h);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $ow, $oh);

                    if ($type === IMAGETYPE_JPEG) {
                        imagejpeg($dst, $targetAbs, 85);
                    } elseif ($type === IMAGETYPE_PNG) {
                        imagepng($dst, $targetAbs, 6);
                    } elseif ($type === IMAGETYPE_WEBP && function_exists('imagewebp')) {
                        imagewebp($dst, $targetAbs, 85);
                    }

                    imagedestroy($src);
                    imagedestroy($dst);
                    $message = 'Slika je uspešno resize-ovana.';
                }
            }
        } else {
            $error = 'Resize zahteva GD ekstenziju i validne dimenzije.';
        }
    }

    if ($action === 'upload_new' && isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = $_POST['target_dir'] ?? 'blog/uploads';
        $allowedTarget = realpath(__DIR__ . '/../../' . $targetDir);
        if ($allowedTarget && is_allowed_path($allowedTarget, $allowedRoots)) {
            $filename = time() . '-' . preg_replace('/\s+/', '-', basename($_FILES['new_image']['name']));
            $dest = $allowedTarget . DIRECTORY_SEPARATOR . $filename;
            if (move_uploaded_file($_FILES['new_image']['tmp_name'], $dest)) {
                $message = 'Nova slika je uploadovana: ' . rel_from_root($dest);
            }
        }
    }
}

$images = [];
foreach ($allowedRoots as $root) {
    $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($iter as $f) {
        if ($f->isFile() && preg_match('/\.(jpe?g|png|gif|webp)$/i', $f->getFilename())) {
            $rel = rel_from_root($f->getPathname());
            $images[] = ['rel' => $rel, 'abs' => $f->getPathname()];
        }
    }
}
usort($images, fn($a,$b)=>strcmp($a['rel'],$b['rel']));

$current = 'media';
?>
<!DOCTYPE html><html lang="sr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Media manager</title><link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css"></head>
<body><main class="admin-shell"><?php include 'admin_sidebar.php'; ?><section class="admin-content">
<section class="topbar"><div><h1>Media manager</h1><p class="muted">Upravljanje svim slikama: alt/title, zamena, brisanje, upload i resize.</p></div></section>
<?php if ($message): ?><div class="alert alert-success"><?= admin_esc($message); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= admin_esc($error); ?></div><?php endif; ?>

<section class="section"><div class="section-header"><h2>Upload nove slike</h2></div><div style="padding:14px;">
<form method="POST" enctype="multipart/form-data" class="form-grid">
<input type="hidden" name="action" value="upload_new">
<div class="form-group"><label>Folder</label><select name="target_dir"><option value="blog/uploads">blog/uploads</option><option value="img">img</option><option value="gallery">gallery</option></select></div>
<div class="form-group"><label>Slika</label><input type="file" name="new_image" required></div>
<div class="form-group full"><button class="btn btn-primary" type="submit">Upload</button></div>
</form></div></section>

<section class="section"><div class="section-header"><h2>Sve slike (<?= count($images); ?>)</h2></div><div class="table-wrap"><table class="table"><thead><tr><th>Preview</th><th>Putanja</th><th>Meta</th><th>Akcije</th></tr></thead><tbody>
<?php foreach ($images as $img): $m = $imageMeta[$img['rel']] ?? ['alt'=>'','title'=>'']; ?>
<tr>
<td><img class="img-thumb" src="<?= admin_esc(getSiteBaseUrl() . '/' . str_replace(' ', '%20', $img['rel'])); ?>" alt=""></td>
<td><code><?= admin_esc($img['rel']); ?></code></td>
<td>
<form method="POST" class="form-grid" style="grid-template-columns:1fr;">
<input type="hidden" name="action" value="save_meta"><input type="hidden" name="target" value="<?= admin_esc($img['rel']); ?>">
<input type="text" name="alt" placeholder="Alt" value="<?= admin_esc($m['alt'] ?? ''); ?>">
<input type="text" name="title" placeholder="Title" value="<?= admin_esc($m['title'] ?? ''); ?>">
<button class="btn btn-secondary btn-sm" type="submit">Sačuvaj meta</button>
</form>
</td>
<td>
<form method="POST" enctype="multipart/form-data" style="margin-bottom:8px;"><input type="hidden" name="action" value="replace_image"><input type="hidden" name="target" value="<?= admin_esc($img['rel']); ?>"><input type="file" name="replacement" required><button class="btn btn-info btn-sm" type="submit">Zameni</button></form>
<form method="POST" style="margin-bottom:8px;"><input type="hidden" name="action" value="resize_image"><input type="hidden" name="target" value="<?= admin_esc($img['rel']); ?>"><input type="number" name="width" placeholder="W" style="width:80px;"> <input type="number" name="height" placeholder="H" style="width:80px;"> <button class="btn btn-warning btn-sm" type="submit">Resize</button></form>
<form method="POST" onsubmit="return confirm('Obrisati sliku?')"><input type="hidden" name="action" value="delete_image"><input type="hidden" name="target" value="<?= admin_esc($img['rel']); ?>"><button class="btn btn-danger btn-sm" type="submit">Obriši</button></form>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div></section>
</section></main></body></html>

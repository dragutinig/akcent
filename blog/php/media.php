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

function rel_from_root($path)
{
    $root = realpath(__DIR__ . '/../..');
    return ltrim(str_replace($root, '', $path), DIRECTORY_SEPARATOR);
}

function is_allowed_path($path, $roots)
{
    $real = realpath($path);
    if ($real === false) {
        return false;
    }

    foreach ($roots as $root) {
        if (strpos($real, $root) === 0) {
            return true;
        }
    }

    return false;
}

function create_image_resource($targetAbs, $type)
{
    if ($type === IMAGETYPE_JPEG) {
        return imagecreatefromjpeg($targetAbs);
    }

    if ($type === IMAGETYPE_PNG) {
        return imagecreatefrompng($targetAbs);
    }

    if ($type === IMAGETYPE_WEBP && function_exists('imagecreatefromwebp')) {
        return imagecreatefromwebp($targetAbs);
    }

    return null;
}


function image_default_text($rel)
{
    $base = pathinfo($rel, PATHINFO_FILENAME);
    $base = str_replace(['-', '_'], ' ', $base);
    return ucwords(trim($base));
}

function save_image_resource($dst, $targetAbs, $type)
{
    if ($type === IMAGETYPE_JPEG) {
        return imagejpeg($dst, $targetAbs, 85);
    }

    if ($type === IMAGETYPE_PNG) {
        return imagepng($dst, $targetAbs, 6);
    }

    if ($type === IMAGETYPE_WEBP && function_exists('imagewebp')) {
        return imagewebp($dst, $targetAbs, 85);
    }

    return false;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $targetRel = trim(isset($_POST['target']) ? $_POST['target'] : '');
    $targetAbs = realpath(__DIR__ . '/../../' . $targetRel);

    if ($action === 'save_meta' && $targetAbs && is_allowed_path($targetAbs, $allowedRoots)) {
        $imageMeta[$targetRel] = [
            'alt' => trim(isset($_POST['alt']) ? $_POST['alt'] : ''),
            'title' => trim(isset($_POST['title']) ? $_POST['title'] : ''),
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
        $w = (int) (isset($_POST['width']) ? $_POST['width'] : 0);
        $h = (int) (isset($_POST['height']) ? $_POST['height'] : 0);

        if ($w > 0 && $h > 0 && extension_loaded('gd')) {
            $info = getimagesize($targetAbs);
            if ($info) {
                $ow = $info[0];
                $oh = $info[1];
                $type = $info[2];
                $src = create_image_resource($targetAbs, $type);
                if ($src) {
                    $dst = imagecreatetruecolor($w, $h);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $ow, $oh);
                    if (save_image_resource($dst, $targetAbs, $type)) {
                        $message = 'Slika je uspešno resize-ovana.';
                    } else {
                        $error = 'Format slike nije podržan za resize.';
                    }
                    imagedestroy($src);
                    imagedestroy($dst);
                } else {
                    $error = 'Format slike nije podržan za resize.';
                }
            }
        } else {
            $error = 'Resize zahteva GD ekstenziju i validne dimenzije.';
        }
    }

    if ($action === 'upload_new' && isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = isset($_POST['target_dir']) ? $_POST['target_dir'] : 'blog/uploads';
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

usort($images, function ($a, $b) {
    return strcmp($a['rel'], $b['rel']);
});

$current = 'media';
?>
<!DOCTYPE html><html lang="sr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Media manager</title><link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css"></head>
<body><main class="admin-shell"><?php include 'admin_sidebar.php'; ?><section class="admin-content">
<section class="topbar"><div><h1>Media manager</h1><p class="muted">Upravljanje svim slikama: automatski predlozi alt/title, pregled dimenzija, zamena, brisanje, upload i resize.</p></div></section>
<?php if ($message): ?><div class="alert alert-success"><?= admin_esc($message); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= admin_esc($error); ?></div><?php endif; ?>

<section class="section"><div class="section-header"><h2>Upload nove slike</h2></div><div style="padding:14px;">
<form method="POST" enctype="multipart/form-data" class="form-grid">
<input type="hidden" name="action" value="upload_new">
<div class="form-group"><label>Folder</label><select name="target_dir"><option value="blog/uploads">blog/uploads</option><option value="img">img</option><option value="gallery">gallery</option></select></div>
<div class="form-group"><label>Slika</label><input type="file" name="new_image" required></div>
<div class="form-group full"><button class="btn btn-primary" type="submit">Upload</button></div>
</form></div></section>

<section class="section"><div class="section-header"><h2>Sve slike (<?= count($images); ?>)</h2></div><div class="table-wrap"><table class="table"><thead><tr><th>Preview</th><th>Putanja</th><th>Dimenzije</th><th>Meta</th><th>Akcije</th></tr></thead><tbody>
<?php foreach ($images as $img): $m = isset($imageMeta[$img['rel']]) ? $imageMeta[$img['rel']] : ['alt' => '', 'title' => '']; $defaultText = image_default_text($img['rel']); $size = @getimagesize($img['abs']); $dimensions = $size ? ($size[0] . ' x ' . $size[1]) : '-'; ?>
<tr>
<td><button type="button" class="media-open" data-src="<?= admin_esc(getSiteBaseUrl() . '/' . str_replace(' ', '%20', $img['rel'])); ?>" data-alt="<?= admin_esc($defaultText); ?>" style="border:0;background:transparent;padding:0;cursor:zoom-in;"><img class="img-thumb" src="<?= admin_esc(getSiteBaseUrl() . '/' . str_replace(' ', '%20', $img['rel'])); ?>" alt=""></button></td>
<td><code><?= admin_esc($img['rel']); ?></code></td>
<td><strong><?= admin_esc($dimensions); ?></strong></td>
<td>
<form method="POST" class="form-grid" style="grid-template-columns:1fr;">
<input type="hidden" name="action" value="save_meta"><input type="hidden" name="target" value="<?= admin_esc($img['rel']); ?>">
<input type="text" name="alt" value="<?= admin_esc(trim((string) (isset($m['alt']) ? $m['alt'] : '')) !== '' ? $m['alt'] : $defaultText); ?>" placeholder="<?= admin_esc($defaultText); ?>">
<input type="text" name="title" value="<?= admin_esc(trim((string) (isset($m['title']) ? $m['title'] : '')) !== '' ? $m['title'] : $defaultText); ?>" placeholder="<?= admin_esc($defaultText); ?>">
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

<div id="mediaModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:9999;align-items:center;justify-content:center;padding:24px;">
  <div style="position:relative;max-width:95vw;max-height:95vh;">
    <button id="mediaModalClose" type="button" class="btn btn-danger btn-sm" style="position:absolute;top:-40px;right:0;">Zatvori ✕</button>
    <img id="mediaModalImg" src="" alt="" style="max-width:95vw;max-height:90vh;border-radius:10px;border:2px solid #475569;">
  </div>
</div>
<script>
(function(){
  const modal=document.getElementById('mediaModal');
  const img=document.getElementById('mediaModalImg');
  const closeBtn=document.getElementById('mediaModalClose');
  document.querySelectorAll('.media-open').forEach(btn=>{
    btn.addEventListener('click',()=>{
      img.src=btn.getAttribute('data-src');
      img.alt=btn.getAttribute('data-alt')||'';
      modal.style.display='flex';
    });
  });
  closeBtn.addEventListener('click',()=> modal.style.display='none');
  modal.addEventListener('click',(e)=>{ if(e.target===modal){ modal.style.display='none'; }});
})();
</script>
</section></main></body></html>

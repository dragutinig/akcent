<?php
require_once 'config.php';
require_once 'admin_bootstrap.php';
require_once 'Database.php';
require_once 'ProjectRepository.php';

$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();

$modelStorageAbs = realpath(__DIR__ . '/..') . '/project-models';
$modelStorageRel = 'blog/project-models';
$imageStorageAbs = realpath(__DIR__ . '/..') . '/uploads/projects';
$imageStorageRel = 'blog/uploads/projects';

if (!is_dir($modelStorageAbs)) {
    mkdir($modelStorageAbs, 0777, true);
}
if (!is_dir($imageStorageAbs)) {
    mkdir($imageStorageAbs, 0777, true);
}

function pslug($text)
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
    return trim($text, '-') ?: 'projekat';
}

function find_model_entry_html($directoryAbs, $folderName)
{
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directoryAbs, FilesystemIterator::SKIP_DOTS)
    );

    $fallback = '';
    foreach ($iter as $f) {
        if (!$f->isFile()) {
            continue;
        }

        if (!preg_match('/\.html?$/i', $f->getFilename())) {
            continue;
        }

        $full = $f->getPathname();
        $rel = ltrim(str_replace($directoryAbs, '', $full), DIRECTORY_SEPARATOR);
        if (strcasecmp($f->getFilename(), $folderName . '.html') === 0) {
            return str_replace(DIRECTORY_SEPARATOR, '/', $rel);
        }
        if ($fallback === '') {
            $fallback = str_replace(DIRECTORY_SEPARATOR, '/', $rel);
        }
    }

    return $fallback;
}

function default_image_text($name)
{
    $base = pathinfo($name, PATHINFO_FILENAME);
    $base = str_replace(array('-', '_'), ' ', $base);
    return ucwords(trim($base));
}

function extract_model_archive($tmpPath, $slug, $sourceName, $modelStorageAbs, $modelStorageRel)
{
    if (!class_exists('ZipArchive')) {
        return array('error' => 'PHP ZipArchive ekstenzija nije dostupna na serveru.');
    }

    $zip = new ZipArchive();
    if ($zip->open($tmpPath) !== true) {
        return array('error' => 'Neuspešno otvaranje ZIP arhive: ' . $sourceName);
    }

    $safeFolder = $slug . '-' . time() . '-' . substr(sha1($sourceName . microtime(true)), 0, 8);
    $destination = $modelStorageAbs . '/' . $safeFolder;
    mkdir($destination, 0777, true);
    $zip->extractTo($destination);
    $zip->close();

    $entry = find_model_entry_html($destination, $slug);
    if ($entry === '') {
        return array('error' => 'ZIP je raspakovan, ali nije pronađen .html fajl za 3D prikaz: ' . $sourceName);
    }

    return array('path' => str_replace('\\', '/', $modelStorageRel . '/' . $safeFolder . '/' . $entry));
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $repo->deleteProject($id);
            $message = 'Projekat je obrisan.';
        }
    }

    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        $slug = pslug(trim($_POST['slug'] ?? '') !== '' ? $_POST['slug'] : $title);
        $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $blogPostUrl = trim($_POST['blog_post_url'] ?? '');

        if ($title === '') {
            $error = 'Naslov je obavezan.';
        } elseif ($metaTitle === '' || $metaDescription === '') {
            $error = 'Meta title i meta description su obavezni zbog SEO.';
        } else {
            $modelLabels = $_POST['model_label'] ?? array();
            $modelsToSave = array();
            if (isset($_FILES['model_archives']) && is_array($_FILES['model_archives']['name'])) {
                $countModels = count($_FILES['model_archives']['name']);
                for ($i = 0; $i < $countModels; $i++) {
                    $errCode = (int) ($_FILES['model_archives']['error'][$i] ?? UPLOAD_ERR_NO_FILE);
                    if ($errCode === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    if ($errCode !== UPLOAD_ERR_OK) {
                        $error = 'Greška pri upload-u 3D ZIP fajla.';
                        break;
                    }

                    $zipName = (string) ($_FILES['model_archives']['name'][$i] ?? 'model.zip');
                    $zipExt = strtolower(pathinfo($zipName, PATHINFO_EXTENSION));
                    if ($zipExt !== 'zip') {
                        $error = 'Dozvoljen je samo ZIP za 3D model.';
                        break;
                    }

                    $tmpPath = (string) $_FILES['model_archives']['tmp_name'][$i];
                    $extract = extract_model_archive($tmpPath, $slug, $zipName, $modelStorageAbs, $modelStorageRel);
                    if (isset($extract['error'])) {
                        $error = $extract['error'];
                        break;
                    }

                    $label = trim((string) ($modelLabels[$i] ?? ''));
                    if ($label === '') {
                        $label = default_image_text($zipName);
                    }

                    $modelsToSave[] = array(
                        'model_label' => $label,
                        'model_path' => $extract['path'],
                    );
                }
            }

            if ($error === '') {
                $firstModelPath = isset($modelsToSave[0]) ? $modelsToSave[0]['model_path'] : null;
                $projectId = $repo->createProject([
                    'title' => $title,
                    'slug' => $slug,
                    'status' => $status,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'model_path' => $firstModelPath,
                    'blog_post_url' => $blogPostUrl,
                    'published_at' => $status === 'published' ? date('Y-m-d H:i:s') : null,
                ]);

                foreach ($modelsToSave as $index => $m) {
                    $repo->addProjectModel($projectId, array(
                        'model_label' => mb_substr((string) ($m['model_label'] ?? ''), 0, 255),
                        'model_path' => (string) ($m['model_path'] ?? ''),
                        'sort_order' => $index,
                    ));
                }

                $altTexts = $_POST['image_alt'] ?? array();
                $titleTexts = $_POST['image_title'] ?? array();

                if (isset($_FILES['project_images']) && is_array($_FILES['project_images']['name'])) {
                    $count = count($_FILES['project_images']['name']);
                    for ($i = 0; $i < $count; $i++) {
                        if (($_FILES['project_images']['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                            continue;
                        }

                        $name = $_FILES['project_images']['name'][$i];
                        $tmp = $_FILES['project_images']['tmp_name'][$i];
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                            continue;
                        }

                        $fileName = $slug . '-' . time() . '-' . $i . '.' . $ext;
                        $destAbs = $imageStorageAbs . '/' . $fileName;
                        if (!move_uploaded_file($tmp, $destAbs)) {
                            continue;
                        }

                        $size = @getimagesize($destAbs);
                        $repo->addProjectImage($projectId, [
                            'image_path' => $imageStorageRel . '/' . $fileName,
                            'alt_text' => trim(($altTexts[$i] ?? '') !== '' ? $altTexts[$i] : default_image_text($name)),
                            'title_text' => trim(($titleTexts[$i] ?? '') !== '' ? $titleTexts[$i] : default_image_text($name)),
                            'sort_order' => $i,
                            'width' => $size ? (int) $size[0] : null,
                            'height' => $size ? (int) $size[1] : null,
                        ]);
                    }
                }

                $message = 'Projekat je sačuvan u bazi.';
            }
        }
    }
}

$projects = $repo->listProjects();
$current = 'projects';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projekti</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css">
    <script src="../js/tinymce/tinymce.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '#content',
            height: 320,
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media paste searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | link image media | bullist numlist | removeformat',
            paste_as_text: true
        });

        function createMetaRow(html) {
          const row = document.createElement('div');
          row.className = 'form-group full';
          row.style.border = '1px solid #334155';
          row.style.padding = '10px';
          row.style.borderRadius = '8px';
          row.innerHTML = html;
          return row;
        }

        const imageRows = document.getElementById('image-rows');
        const addImageBtn = document.getElementById('add-image-row');
        let imageIndex = 0;

        function addImageRow(file = null) {
          const i = imageIndex++;
          const name = file ? file.name : '';
          const placeholder = name ? name.replace(/\.[^.]+$/, '') : 'npr. kuhinja ostrvo';
          const row = createMetaRow(`
            <div style="display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center;">
              <input type="file" name="project_images[]" accept=".jpg,.jpeg,.png,.webp,.gif" >
              <button type="button" class="btn btn-danger btn-sm remove-row">Ukloni</button>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px;">
              <input type="text" name="image_alt[${i}]" placeholder="Alt tekst (${placeholder})">
              <input type="text" name="image_title[${i}]" placeholder="Title (${placeholder})">
            </div>
          `);
          const input = row.querySelector('input[type="file"]');
          if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
          }
          row.querySelector('.remove-row').addEventListener('click', () => row.remove());
          imageRows.appendChild(row);
        }

        addImageBtn.addEventListener('click', () => addImageRow());
        addImageRow();

        const modelRows = document.getElementById('model-rows');
        const addModelBtn = document.getElementById('add-model-row');
        let modelIndex = 0;

        function addModelRow(file = null) {
          const i = modelIndex++;
          const name = file ? file.name : '';
          const placeholder = name ? name.replace(/\.[^.]+$/, '') : 'npr. Kuhinja 3D model';
          const row = createMetaRow(`
            <div style="display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center;">
              <input type="file" name="model_archives[]" accept=".zip" >
              <button type="button" class="btn btn-danger btn-sm remove-row">Ukloni</button>
            </div>
            <div style="display:grid;grid-template-columns:1fr;gap:8px;margin-top:8px;">
              <input type="text" name="model_label[${i}]" placeholder="Naziv modela (${placeholder})">
            </div>
          `);
          const input = row.querySelector('input[type="file"]');
          if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
          }
          row.querySelector('.remove-row').addEventListener('click', () => row.remove());
          modelRows.appendChild(row);
        }

        addModelBtn.addEventListener('click', () => addModelRow());
        addModelRow();
      });
    </script>
</head>
<body>
<main class="admin-shell">
    <?php include 'admin_sidebar.php'; ?>
    <section class="admin-content">
        <section class="topbar">
            <div>
                <h1>Gotovi projekti</h1>
                <p class="muted">Kreiraj SEO optimizovanu projektnu stranicu na glavnom sajtu.</p>
            </div>
        </section>

        <?php if ($message): ?><div class="alert alert-success"><?= admin_esc($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= admin_esc($error); ?></div><?php endif; ?>

        <section class="section">
            <div class="section-header"><h2>Novi projekat</h2></div>
            <div style="padding:14px;">
                <form method="POST" enctype="multipart/form-data" class="form-grid">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group"><label>Naslov</label><input name="title" required></div>
                    <div class="form-group"><label>Slug URL</label><input name="slug" placeholder="opciono"></div>
                    <div class="form-group"><label>Status</label><select name="status"><option value="draft">Draft</option><option value="published">Published</option></select></div>
                    <div class="form-group"><label>Blog post URL (opciono)</label><input name="blog_post_url" placeholder="/blog/kategorija/slug"></div>

                    <div class="form-group"><label>Meta title</label><input name="meta_title" maxlength="255" required></div>
                    <div class="form-group"><label>Meta description</label><input name="meta_description" maxlength="320" required></div>

                    <div class="form-group full"><label>Kratak uvod (excerpt)</label><textarea name="excerpt"></textarea></div>
                    <div class="form-group full"><label>Detaljan opis projekta (editor)</label><textarea id="content" name="content"></textarea></div>

                    <div class="form-group full"><label>3D modeli (ZIP) — dodaj jedan ili više modela, svaki sa svojim imenom</label></div>
                    <div id="model-rows" class="form-group full"></div>
                    <div class="form-group full"><button id="add-model-row" class="btn btn-secondary" type="button">+ Dodaj još 3D modela</button></div>

                    <div class="form-group full"><label>Fotografije projekta — mini galerija sa ALT i TITLE podacima</label></div>
                    <div id="image-rows" class="form-group full"></div>
                    <div class="form-group full"><button id="add-image-row" class="btn btn-secondary" type="button">+ Dodaj još fotografija</button></div>

                    <div class="form-group full"><button class="btn btn-primary" type="submit">Sačuvaj projekat</button></div>
                </form>
            </div>
        </section>

        <section class="section">
            <div class="section-header"><h2>Postojeći projekti</h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Naslov</th><th>Status</th><th>Datum</th><th>SEO</th><th>3D modeli</th><th>Akcije</th></tr></thead>
                    <tbody>
                    <?php foreach ($projects as $p): ?>
                        <tr>
                            <td>
                                <strong><?= admin_esc($p['title']); ?></strong><br>
                                <span class="muted">slug: <?= admin_esc($p['slug']); ?></span>
                            </td>
                            <td><span class="badge <?= $p['status'] === 'published' ? 'badge-published' : 'badge-draft'; ?>"><?= admin_esc($p['status']); ?></span></td>
                            <td><?= admin_esc($p['created_at']); ?></td>
                            <td><small><?= admin_esc((string) $p['meta_title']); ?></small></td>
                            <td><?= (int) count($p['models'] ?? []); ?></td>
                            <td>
                                <a class="btn btn-secondary btn-sm" target="_blank" href="<?= admin_esc(getSiteBaseUrl()); ?>/projekat.php?slug=<?= urlencode($p['slug']); ?>">Pogledaj</a>
                                <form style="display:inline;" method="POST" onsubmit="return confirm('Obrisati projekat?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $p['id']; ?>">
                                    <button class="btn btn-danger btn-sm" type="submit">Obriši</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</main>
</body>
</html>

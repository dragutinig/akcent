<?php
require_once 'config.php';
require_once 'admin_bootstrap.php';
require_once 'Database.php';
require_once 'ProjectRepository.php';

$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();

$storageAbs = realpath(__DIR__ . '/..') . '/private-models';
$storageRel = 'blog/private-models';
if (!is_dir($storageAbs)) {
    mkdir($storageAbs, 0777, true);
}

function rrmdir($dir)
{
    if (!is_dir($dir)) {
        return;
    }
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            rrmdir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

function find_html_recursive($directoryAbs, $preferredName = '')
{
    $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directoryAbs, FilesystemIterator::SKIP_DOTS));
    $fallback = '';
    foreach ($iter as $f) {
        if (!$f->isFile() || !preg_match('/\.html?$/i', $f->getFilename())) {
            continue;
        }
        $full = $f->getPathname();
        $rel = ltrim(str_replace($directoryAbs, '', $full), DIRECTORY_SEPARATOR);
        $rel = str_replace(DIRECTORY_SEPARATOR, '/', $rel);
        if ($preferredName !== '' && strcasecmp($f->getFilename(), $preferredName . '.html') === 0) {
            return $rel;
        }
        if ($fallback === '') {
            $fallback = $rel;
        }
    }
    return $fallback;
}

function normalize_date_value($value, $withTime = false)
{
    $value = trim((string) $value);
    if ($value === '') {
        return null;
    }

    $formats = $withTime
        ? ['Y-m-d\TH:i', 'Y-m-d H:i', 'd.m.Y H:i', 'd/m/Y H:i', 'm/d/Y H:i']
        : ['Y-m-d', 'd.m.Y', 'd/m/Y', 'm/d/Y'];

    foreach ($formats as $format) {
        $dt = DateTime::createFromFormat($format, $value);
        if ($dt instanceof DateTime) {
            return $withTime ? $dt->format('Y-m-d H:i:s') : $dt->format('Y-m-d');
        }
    }

    $timestamp = strtotime($value);
    if ($timestamp !== false) {
        return $withTime ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d', $timestamp);
    }

    return null;
}

$message = '';
$error = '';
$edit = null;

if (isset($_GET['edit'])) {
    $edit = $repo->getClientPreview((int) $_GET['edit']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $existing = $repo->getClientPreview($id);
        if ($existing) {
            $repo->deleteClientPreview($id);
            $modelPath = trim((string) $existing['model_path']);
            if ($modelPath !== '') {
                $baseDirRel = explode('/', $modelPath);
                if (count($baseDirRel) >= 3) {
                    rrmdir($storageAbs . '/' . $baseDirRel[2]);
                }
            }
            $message = '3D preview je obrisan.';
        }
    }

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $clientName = trim($_POST['client_name'] ?? '');
        $label = trim($_POST['model_label'] ?? '');
        $reviewDate = normalize_date_value($_POST['review_date'] ?? '', false);
        $expiresAt = normalize_date_value($_POST['expires_at'] ?? '', true);
        $notes = trim($_POST['notes'] ?? '');

        if ($clientName === '') {
            $error = 'Ime klijenta je obavezno.';
        }

        $uploadedModels = [];
        if ($error === '' && isset($_FILES['model_archives']) && is_array($_FILES['model_archives']['name'])) {
            $count = count($_FILES['model_archives']['name']);
            for ($i = 0; $i < $count; $i++) {
                $errCode = (int) ($_FILES['model_archives']['error'][$i] ?? UPLOAD_ERR_NO_FILE);
                if ($errCode === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                if ($errCode !== UPLOAD_ERR_OK) {
                    $error = 'Greška pri upload-u ZIP fajla.';
                    break;
                }

                $zipName = (string) ($_FILES['model_archives']['name'][$i] ?? 'model.zip');
                if (strtolower(pathinfo($zipName, PATHINFO_EXTENSION)) !== 'zip') {
                    $error = 'Dozvoljeni su samo ZIP fajlovi.';
                    break;
                }
                if (!class_exists('ZipArchive')) {
                    $error = 'ZipArchive nije dostupna.';
                    break;
                }

                $defaultLabel = preg_replace('/\.[^.]+$/', '', basename($zipName));
                $itemLabel = trim((string) ($_POST['model_label'][$i] ?? ''));
                if ($itemLabel === '') {
                    $itemLabel = $defaultLabel !== '' ? $defaultLabel : '3D model';
                }

                $tokenPart = bin2hex(random_bytes(6));
                $safeFolder = preg_replace('/[^a-z0-9-]+/i', '-', strtolower($itemLabel)) . '-' . $tokenPart;
                $dest = $storageAbs . '/' . $safeFolder;
                mkdir($dest, 0777, true);

                $zip = new ZipArchive();
                if ($zip->open((string) $_FILES['model_archives']['tmp_name'][$i]) !== true) {
                    $error = 'Ne mogu da otvorim ZIP arhivu.';
                    break;
                }
                $zip->extractTo($dest);
                $zip->close();

                $entry = find_html_recursive($dest, preg_replace('/[^a-z0-9-]+/i', '-', strtolower($itemLabel)));
                if ($entry === '') {
                    $error = 'U ZIP-u nije pronađen HTML fajl.';
                    break;
                }

                $uploadedModels[] = [
                    'model_label' => $itemLabel,
                    'model_path' => $storageRel . '/' . $safeFolder . '/' . $entry,
                ];
            }
        }

        if ($error === '' && $reviewDate === null && trim((string) ($_POST['review_date'] ?? '')) !== '') {
            $error = 'Datum nije u dobrom formatu.';
        }
        if ($error === '' && $expiresAt === null && trim((string) ($_POST['expires_at'] ?? '')) !== '') {
            $error = 'Datum isteka nije u dobrom formatu.';
        }

        if ($error === '') {
            if ($id > 0) {
                $modelPath = trim($_POST['existing_model_path'] ?? '');
                if (!empty($uploadedModels)) {
                    $modelPath = $uploadedModels[0]['model_path'];
                    $label = $uploadedModels[0]['model_label'];
                }
                if ($modelPath === '') {
                    $error = 'Dodaj ZIP model ili postojeću putanju modela.';
                } else {
                    $repo->updateClientPreview($id, [
                        'client_name' => $clientName,
                        'model_label' => $label !== '' ? $label : '3D model',
                        'model_path' => $modelPath,
                        'review_date' => $reviewDate,
                        'expires_at' => $expiresAt,
                        'notes' => $notes,
                    ]);
                    $message = '3D preview je ažuriran.';
                }
            } else {
                if (empty($uploadedModels)) {
                    $error = 'Dodaj barem jedan ZIP 3D model.';
                } else {
                    foreach ($uploadedModels as $model) {
                        $repo->createClientPreview([
                            'client_name' => $clientName,
                            'model_label' => $model['model_label'],
                            'preview_token' => bin2hex(random_bytes(16)),
                            'model_path' => $model['model_path'],
                            'review_date' => $reviewDate,
                            'expires_at' => $expiresAt,
                            'notes' => $notes,
                        ]);
                    }
                    $message = count($uploadedModels) > 1
                        ? 'Kreirano je više 3D preview linkova.'
                        : '3D preview je kreiran.';
                }
            }
        }
    }
}

$rows = $repo->listClientPreviews();
$current = 'preview3d';
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview 3D | Admin</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<main class="admin-shell">
    <?php include 'admin_sidebar.php'; ?>
    <section class="admin-content">
        <section class="topbar">
            <div>
                <h1>Preview 3D (privatno)</h1>
                <p class="muted">Linkovi za klijente. Nisu linkovani sa javnog sajta i imaju noindex.</p>
            </div>
        </section>

        <?php if ($message): ?><div class="alert alert-success"><?= admin_esc($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= admin_esc($error); ?></div><?php endif; ?>

        <section class="section">
            <div class="section-header"><h2><?= $edit ? 'Izmena preview linka' : 'Novi preview link'; ?></h2></div>
            <div style="padding:14px;">
                <form method="POST" enctype="multipart/form-data" class="form-grid">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0); ?>">
                    <input type="hidden" name="existing_model_path" value="<?= admin_esc((string) ($edit['model_path'] ?? '')); ?>">

                    <div class="form-group"><label>Ime i prezime klijenta</label><input name="client_name" required value="<?= admin_esc((string) ($edit['client_name'] ?? '')); ?>"></div>
                    <div class="form-group"><label>Naziv modela (za izmenu jednog modela)</label><input name="model_label" value="<?= admin_esc((string) ($edit['model_label'] ?? '')); ?>"></div>
                    <div class="form-group"><label>Datum</label><input class="js-date" type="text" name="review_date" placeholder="dd.mm.gggg" value="<?= admin_esc((string) ($edit['review_date'] ?? '')); ?>"></div>
                    <div class="form-group"><label>Ističe (opciono)</label><input class="js-datetime" type="text" name="expires_at" placeholder="dd.mm.gggg hh:mm" value="<?= admin_esc(isset($edit['expires_at']) && $edit['expires_at'] ? date('d.m.Y H:i', strtotime($edit['expires_at'])) : ''); ?>"></div>
                    <div class="form-group full"><label>ZIP 3D modela (može više)</label><input id="model_archives" type="file" name="model_archives[]" accept=".zip" multiple></div>
                    <div id="models-meta-wrap" class="form-group full"></div>
                    <div class="form-group full"><label>Napomena</label><textarea name="notes"><?= admin_esc((string) ($edit['notes'] ?? '')); ?></textarea></div>
                    <div class="form-group full"><button class="btn btn-primary" type="submit">Sačuvaj</button></div>
                </form>
            </div>
        </section>

        <section class="section">
            <div class="section-header"><h2>Svi 3D preview linkovi</h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Klijent</th><th>Model</th><th>Datum</th><th>Link</th><th>Akcije</th></tr></thead>
                    <tbody>
                    <?php foreach ($rows as $r):
                        $url = getBlogBaseUrl() . '/php/client_3d_view.php?token=' . urlencode($r['preview_token']);
                    ?>
                        <tr>
                            <td><?= admin_esc($r['client_name']); ?></td>
                            <td><?= admin_esc($r['model_label']); ?></td>
                            <td><?= admin_esc((string) ($r['review_date'] ?? $r['created_at'])); ?></td>
                            <td>
                                <input type="text" value="<?= admin_esc($url); ?>" id="link-<?= (int) $r['id']; ?>" style="width:320px;">
                                <button class="btn btn-secondary btn-sm" type="button" onclick="copyLink('link-<?= (int) $r['id']; ?>')">Kopiraj</button>
                            </td>
                            <td>
                                <a class="btn btn-info btn-sm" target="_blank" href="<?= admin_esc($url); ?>">Otvori</a>
                                <a class="btn btn-secondary btn-sm" href="client_3d_models.php?edit=<?= (int) $r['id']; ?>">Izmeni</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Obrisati ovaj 3D preview?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $r['id']; ?>">
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function copyLink(id) {
  const el = document.getElementById(id);
  el.select();
  document.execCommand('copy');
}

flatpickr('.js-date', { dateFormat: 'd.m.Y', allowInput: true });
flatpickr('.js-datetime', { enableTime: true, time_24hr: true, dateFormat: 'd.m.Y H:i', allowInput: true });

const modelsInput = document.getElementById('model_archives');
const modelsWrap = document.getElementById('models-meta-wrap');
if (modelsInput && modelsWrap) {
  modelsInput.addEventListener('change', function () {
    modelsWrap.innerHTML = '';
    Array.from(modelsInput.files).forEach((file, i) => {
      const row = document.createElement('div');
      row.className = 'form-group full';
      row.style.border = '1px solid #334155';
      row.style.padding = '10px';
      row.style.borderRadius = '8px';
      row.innerHTML = `<strong>${file.name}</strong>
      <div style="display:grid;grid-template-columns:1fr;gap:8px;margin-top:8px;">
        <input type="text" name="model_label[${i}]" placeholder="Naziv 3D modela (auto: ${file.name.replace(/\.[^.]+$/, '')})">
      </div>`;
      modelsWrap.appendChild(row);
    });
  });
}
</script>
</body>
</html>

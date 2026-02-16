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
    $items = scandir($dir);
    foreach ($items as $item) {
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
                    $folder = $baseDirRel[2];
                    rrmdir($storageAbs . '/' . $folder);
                }
            }
            $message = '3D preview je obrisan.';
        }
    }

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $clientName = trim($_POST['client_name'] ?? '');
        $label = trim($_POST['model_label'] ?? '');
        $reviewDate = trim($_POST['review_date'] ?? '');
        $expiresAt = trim($_POST['expires_at'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if ($clientName === '' || $label === '') {
            $error = 'Ime klijenta i naziv modela su obavezni.';
        } else {
            $modelPath = trim($_POST['existing_model_path'] ?? '');

            if (isset($_FILES['model_archive']) && $_FILES['model_archive']['error'] === UPLOAD_ERR_OK) {
                if (!class_exists('ZipArchive')) {
                    $error = 'ZipArchive nije dostupna.';
                } else {
                    $tokenPart = bin2hex(random_bytes(6));
                    $safeFolder = preg_replace('/[^a-z0-9-]+/i', '-', strtolower($label)) . '-' . $tokenPart;
                    $dest = $storageAbs . '/' . $safeFolder;
                    mkdir($dest, 0777, true);

                    $zip = new ZipArchive();
                    if ($zip->open($_FILES['model_archive']['tmp_name']) === true) {
                        $zip->extractTo($dest);
                        $zip->close();
                        $entry = find_html_recursive($dest, preg_replace('/[^a-z0-9-]+/i', '-', strtolower($label)));
                        if ($entry === '') {
                            $error = 'U ZIP-u nije pronađen HTML fajl.';
                        } else {
                            $modelPath = $storageRel . '/' . $safeFolder . '/' . $entry;
                        }
                    } else {
                        $error = 'Ne mogu da otvorim ZIP arhivu.';
                    }
                }
            }

            if ($error === '') {
                if ($modelPath === '') {
                    $error = 'Dodaj ZIP model ili postojeću putanju modela.';
                } else {
                    if ($id > 0) {
                        $repo->updateClientPreview($id, [
                            'client_name' => $clientName,
                            'model_label' => $label,
                            'model_path' => $modelPath,
                            'review_date' => $reviewDate !== '' ? $reviewDate : null,
                            'expires_at' => $expiresAt !== '' ? $expiresAt : null,
                            'notes' => $notes,
                        ]);
                        $message = '3D preview je ažuriran.';
                    } else {
                        $repo->createClientPreview([
                            'client_name' => $clientName,
                            'model_label' => $label,
                            'preview_token' => bin2hex(random_bytes(16)),
                            'model_path' => $modelPath,
                            'review_date' => $reviewDate !== '' ? $reviewDate : null,
                            'expires_at' => $expiresAt !== '' ? $expiresAt : null,
                            'notes' => $notes,
                        ]);
                        $message = '3D preview je kreiran.';
                    }
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
                    <div class="form-group"><label>Naziv modela</label><input name="model_label" required value="<?= admin_esc((string) ($edit['model_label'] ?? '')); ?>"></div>
                    <div class="form-group"><label>Datum</label><input type="date" name="review_date" value="<?= admin_esc((string) ($edit['review_date'] ?? '')); ?>"></div>
                    <div class="form-group"><label>Ističe (opciono)</label><input type="datetime-local" name="expires_at" value="<?= admin_esc(isset($edit['expires_at']) && $edit['expires_at'] ? str_replace(' ', 'T', substr($edit['expires_at'], 0, 16)) : ''); ?>"></div>
                    <div class="form-group full"><label>ZIP 3D modela</label><input type="file" name="model_archive" accept=".zip"></div>
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
<script>
function copyLink(id) {
  var el = document.getElementById(id);
  el.select();
  document.execCommand('copy');
}
</script>
</body>
</html>

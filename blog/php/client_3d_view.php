<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'ProjectRepository.php';

header('X-Robots-Tag: noindex, nofollow, noarchive', true);

$db = (new Database())->connect();
$repo = new ProjectRepository($db);
$repo->ensureSchema();

$token = trim($_GET['token'] ?? '');
if ($token === '') {
    http_response_code(404);
    exit('Link nije validan.');
}

$preview = $repo->getClientPreviewByToken($token);
if (!$preview) {
    http_response_code(404);
    exit('Model nije pronađen.');
}

if (!empty($preview['expires_at']) && strtotime($preview['expires_at']) < time()) {
    http_response_code(410);
    exit('Link je istekao.');
}

$modelUrl = strpos($preview['model_path'], 'http') === 0
    ? $preview['model_path']
    : buildPublicUrlFromPath($preview['model_path']);
?>
<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>3D pregled | <?php echo htmlspecialchars($preview['client_name'], ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; background:#0b1220; color:#e2e8f0; }
        .top { padding:12px 16px; background:#111827; border-bottom:1px solid #334155; }
        .wrap { height: calc(100vh - 56px); }
        iframe { width:100%; height:100%; border:0; background:#fff; }
        .meta { color:#94a3b8; font-size:13px; }
    </style>
</head>
<body>
    <div class="top">
      <strong><?php echo htmlspecialchars($preview['model_label'], ENT_QUOTES, 'UTF-8'); ?></strong> – <?php echo htmlspecialchars($preview['client_name'], ENT_QUOTES, 'UTF-8'); ?>
      <div class="meta">Privatan 3D pregled za klijenta</div>
    </div>
    <div class="wrap">
      <iframe src="<?php echo htmlspecialchars($modelUrl, ENT_QUOTES, 'UTF-8'); ?>"></iframe>
    </div>
</body>
</html>

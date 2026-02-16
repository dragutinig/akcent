<?php
require_once 'Database.php';
require_once 'config.php';
require_once 'admin_bootstrap.php';

$postId = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
if ($postId <= 0) {
    die('Nedostaje ID posta za preview.');
}

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT p.id, p.title, p.content, p.featured_image, p.published_at, p.status, p.meta_title, p.meta_description, p.slug AS post_slug, c.name AS category_name, c.slug AS category_slug
                     FROM posts p
                     LEFT JOIN categories c ON c.id = p.category_id
                     WHERE p.id = ? LIMIT 1");
$stmt->bind_param('i', $postId);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    die('Post nije pronađen.');
}

$tags = [];
$tagStmt = $db->prepare('SELECT t.name FROM tags t JOIN posttags pt ON t.id = pt.tag_id WHERE pt.post_id = ?');
$tagStmt->bind_param('i', $postId);
$tagStmt->execute();
$tagResult = $tagStmt->get_result();
while ($row = $tagResult->fetch_assoc()) {
    $tags[] = $row['name'];
}

$metaTitle = trim((string) ($post['meta_title'] ?? '')) !== '' ? $post['meta_title'] : $post['title'];
$metaDescription = trim((string) ($post['meta_description'] ?? ''));
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/css/style.css">
    <title><?php echo htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8'); ?> (Preview)</title>
    <meta name="robots" content="noindex, nofollow">
    <?php if ($metaDescription !== ''): ?>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/template.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/share.css">
    <script src="<?php echo htmlspecialchars(getBlogBasePath()); ?>/js/main.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
<div style="background:#111827;color:#e5e7eb;padding:10px 14px;font-size:14px;">
    <strong>Preview režim:</strong> ovaj post je trenutno <strong><?php echo htmlspecialchars($post['status'], ENT_QUOTES, 'UTF-8'); ?></strong>.
    <a href="edit_post.php?post_id=<?php echo (int) $postId; ?>" style="margin-left:10px; color:#93c5fd;">Nazad na uređivanje</a>
</div>
<article>
    <h1><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="published-at"><?php echo $post['published_at'] ? date('F j, Y', strtotime($post['published_at'])) : date('F j, Y'); ?></p>
    <div class="post-content"><?php echo $post['content']; ?></div>
    <?php if (!empty($tags)): ?>
    <p><strong>Tagovi:</strong> <?php echo htmlspecialchars(implode(', ', $tags), ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <div class="col-5 col-sm-4">
        <a href="dashboard.php" class="button-37 col-12 btn btn-secondary btn-lg" style="color:#fff !important; font-size:1rem; margin-top:20px">Nazad na dashboard</a>
    </div>
</article>
<?php include '../../komponente/cookie-banner.php'; ?>
<?php include '../../komponente/footer.php'; ?>
</body>
</html>

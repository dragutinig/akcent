<?php
require_once 'Database.php';
require_once 'config.php';

$requestUri = $_SERVER['REQUEST_URI'] ?? '';

if (isset($_GET['category'], $_GET['slug'])) {
    $categorySlug = trim((string) $_GET['category']);
    $postSlug = trim((string) $_GET['slug']);
} elseif (preg_match('#(?:^|/)blog/([^/]+)/([^/]+)/?$#', parse_url($requestUri, PHP_URL_PATH) ?: '', $matches)) {
    $categorySlug = $matches[1];
    $postSlug = $matches[2];
} else {
    http_response_code(400);
    exit('Invalid URL format.');
}

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    http_response_code(500);
    exit('Connection failed.');
}

$conn->query("CREATE TABLE IF NOT EXISTS post_comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT UNSIGNED NOT NULL,
    author_name VARCHAR(120) NOT NULL,
    author_email VARCHAR(190) DEFAULT NULL,
    comment_text TEXT NOT NULL,
    status ENUM('approved','pending','spam') NOT NULL DEFAULT 'approved',
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_post_comments_post_status (post_id, status, created_at),
    CONSTRAINT fk_post_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$postQuery = "SELECT posts.id, posts.title, posts.content, posts.featured_image, posts.published_at, categories.name AS category_name,
           posts.meta_title, posts.meta_description, posts.slug AS post_slug, categories.slug AS category_slug
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    WHERE posts.slug = ? AND categories.slug = ? AND posts.status = 'published'";

$stmt = $conn->prepare($postQuery);
$stmt->bind_param('ss', $postSlug, $categorySlug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    exit('Post not found.');
}

$post = $result->fetch_assoc();
$postId = (int) $post['id'];

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'add_comment')) {
    start_secure_session();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $comment = trim((string) ($_POST['comment'] ?? ''));
    $website = trim((string) ($_POST['website'] ?? ''));

    if ($website !== '') {
        $flash = 'Komentar nije sačuvan.';
    } elseif ($name === '' || $comment === '') {
        $flash = 'Popuni ime i komentar.';
    } else {
        $rateKey = 'post_comment_' . $postId;
        $last = (int) ($_SESSION[$rateKey] ?? 0);
        if (time() - $last < 20) {
            $flash = 'Sačekaj malo pre narednog komentara.';
        } else {
            $insert = $conn->prepare('INSERT INTO post_comments (post_id, author_name, author_email, comment_text, status, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $status = 'approved';
            $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
            $ua = mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
            $author = mb_substr($name, 0, 120);
            $authorEmail = mb_substr($email, 0, 190);
            $commentText = mb_substr($comment, 0, 1500);
            $insert->bind_param('issssss', $postId, $author, $authorEmail, $commentText, $status, $ip, $ua);
            $insert->execute();
            $_SESSION[$rateKey] = time();
            $flash = 'Komentar je objavljen.';
        }
    }
}

$relatedStmt = $conn->prepare("SELECT posts.title, posts.slug, posts.featured_image, posts.content, categories.slug AS category_slug
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    WHERE posts.status = 'published' AND categories.slug = ? AND posts.slug <> ?
    ORDER BY posts.published_at DESC
    LIMIT 3");
$relatedStmt->bind_param('ss', $categorySlug, $postSlug);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
$relatedPosts = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedPosts[] = $row;
}

$tagsQuery = "SELECT tags.name
    FROM tags
    JOIN posttags ON tags.id = posttags.tag_id
    WHERE posttags.post_id = ?";
$tagsStmt = $conn->prepare($tagsQuery);
$tagsStmt->bind_param('i', $postId);
$tagsStmt->execute();
$tagsResult = $tagsStmt->get_result();
$tags = [];
while ($tag = $tagsResult->fetch_assoc()) {
    $tags[] = $tag['name'];
}
$tagsString = implode(', ', $tags);

$commentsStmt = $conn->prepare('SELECT author_name, comment_text, created_at FROM post_comments WHERE post_id = ? AND status = "approved" ORDER BY created_at DESC LIMIT 30');
$commentsStmt->bind_param('i', $postId);
$commentsStmt->execute();
$commentsRes = $commentsStmt->get_result();
$comments = [];
while ($c = $commentsRes->fetch_assoc()) {
    $comments[] = $c;
}

$absolutePath = resolveImageUrl((string) $post['featured_image']);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HDLXHWERJK"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);} 
        gtag('js', new Date());
        gtag('config', 'G-HDLXHWERJK');
    </script>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['meta_title']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/share.css">
    <link rel="stylesheet" href="../css/template.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="<?php echo htmlspecialchars(getBlogBaseUrl()); ?>/<?php echo htmlspecialchars($categorySlug); ?>/<?php echo htmlspecialchars($postSlug); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars($post['meta_description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($tagsString); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($post['meta_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($post['meta_description']); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($absolutePath); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars(getBlogBaseUrl() . '/' . $categorySlug . '/' . $postSlug); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <style>
        .recommended-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin-top:10px; }
        .recommended-card { border:1px solid #e5e7eb; background:#fff; border-radius:10px; overflow:hidden; }
        .recommended-card img { width:100%; height:130px; object-fit:cover; }
        .recommended-card .body { padding:10px; }
        .recommended-card h3 { font-size:1rem; margin:0 0 8px; color:#111827; }
        .recommended-card p { margin:0; color:#4b5563; font-size:.9rem; line-height:1.4; }
        .blog-comments { margin-top:24px; border-top:1px solid #e5e7eb; padding-top:18px; }
        .blog-comment-item { border:1px solid #e5e7eb; border-radius:8px; padding:10px; margin-bottom:8px; background:#fff; }
        footer { background-color:black; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<article>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p class="published-at"><?php echo date('F j, Y', strtotime($post['published_at'])); ?></p>
    <div class="post-content"><?php echo $post['content']; ?></div>

    <?php if (!empty($relatedPosts)): ?>
    <section style="margin-top:26px; border-top:1px solid #e5e7eb; padding-top:20px;">
        <h2 style="font-size:1.3rem; margin-bottom:10px;">Preporučeni blogovi</h2>
        <div class="recommended-grid">
            <?php foreach ($relatedPosts as $rp): ?>
                <a class="recommended-card" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/<?php echo htmlspecialchars($rp['category_slug']); ?>/<?php echo htmlspecialchars($rp['slug']); ?>">
                    <img src="<?php echo htmlspecialchars(resolveImageUrl((string) ($rp['featured_image'] ?? ''))); ?>" alt="<?php echo htmlspecialchars($rp['title']); ?>">
                    <div class="body">
                        <h3><?php echo htmlspecialchars($rp['title']); ?></h3>
                        <p><?php echo htmlspecialchars(mb_substr(trim(strip_tags((string) $rp['content'])), 0, 110)); ?>...</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="blog-comments">
        <h2>Komentari</h2>
        <?php if ($flash): ?><div class="alert alert-info"><?php echo htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <?php foreach ($comments as $c): ?>
            <div class="blog-comment-item">
                <strong><?php echo htmlspecialchars($c['author_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <small class="published-at"><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($c['created_at']))); ?></small>
                <p class="mb-0"><?php echo nl2br(htmlspecialchars($c['comment_text'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
        <?php endforeach; ?>

        <form method="post">
            <input type="hidden" name="action" value="add_comment">
            <input type="text" name="website" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px;">
            <div class="row g-2">
                <div class="col-md-6"><input class="form-control" name="name" placeholder="Ime" required></div>
                <div class="col-md-6"><input class="form-control" type="email" name="email" placeholder="Email (opciono)"></div>
                <div class="col-12"><textarea class="form-control" name="comment" rows="4" placeholder="Komentar" required></textarea></div>
                <div class="col-12"><button class="btn btn-primary" type="submit">Pošalji komentar</button></div>
            </div>
        </form>
    </section>
</article>

<?php include('../../komponente/cookie-banner.php'); ?>
<?php include '../../komponente/footer.php'; ?>
</body>
</html>
<?php $conn->close(); ?>

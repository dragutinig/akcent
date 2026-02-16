<?php
require_once 'Database.php';
require_once 'config.php';

start_secure_session();

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$categorySlug = trim((string) ($_GET['category'] ?? ''));
$postSlug = trim((string) ($_GET['slug'] ?? ''));

if ($postSlug === '' && preg_match('#(?:^|/)blog/([^/]+)/([^/]+)/?$#', parse_url($requestUri, PHP_URL_PATH), $matches)) {
    $categorySlug = $matches[1];
    $postSlug = $matches[2];
}

if ($postSlug === '') {
    http_response_code(404);
    die('Post not found.');
}

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    http_response_code(500);
    exit('Connection failed.');
}

// Upit za postove
if ($categorySlug !== '') {
    $postQuery = "
        SELECT posts.id, posts.title, posts.content, posts.featured_image, posts.published_at, categories.name AS category_name,posts.meta_title,
               posts.meta_description, posts.slug AS post_slug, categories.slug AS category_slug
        FROM posts
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.slug = ? AND categories.slug = ? AND posts.status = 'published'";

    $stmt = $conn->prepare($postQuery);
    $stmt->bind_param('ss', $postSlug, $categorySlug);
} else {
    $postQuery = "
        SELECT posts.id, posts.title, posts.content, posts.featured_image, posts.published_at, categories.name AS category_name,posts.meta_title,
               posts.meta_description, posts.slug AS post_slug, categories.slug AS category_slug
        FROM posts
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.slug = ? AND posts.status = 'published'
        LIMIT 1";

    $stmt = $conn->prepare($postQuery);
    $stmt->bind_param('s', $postSlug);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die('Post not found.');
}

$post = $result->fetch_assoc();
$categorySlug = (string) ($post['category_slug'] ?? $categorySlug);


$relatedStmt = $conn->prepare("SELECT posts.title, posts.slug, categories.slug AS category_slug
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

// Upit za tagove povezane sa postom
$tagsQuery = "
    SELECT tags.name
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

$commentMessage = '';
$commentsStorageMode = 'db';
$tableReady = $conn->query("CREATE TABLE IF NOT EXISTS post_comments_public (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT UNSIGNED NOT NULL,
    author_name VARCHAR(120) NOT NULL,
    author_email VARCHAR(190) DEFAULT NULL,
    comment_text TEXT NOT NULL,
    status ENUM('approved','pending','spam') NOT NULL DEFAULT 'approved',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_post_comments_public (post_id, status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
if ($tableReady === false) {
    $commentsStorageMode = 'file';
}

$commentsFile = __DIR__ . '/../data/post_comments_fallback.json';
if ($commentsStorageMode === 'file' && !is_dir(dirname($commentsFile))) {
    mkdir(dirname($commentsFile), 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment_submit'])) {
    $name = trim($_POST['comment_name'] ?? '');
    $email = trim($_POST['comment_email'] ?? '');
    $text = trim($_POST['comment_text'] ?? '');
    $website = trim($_POST['website'] ?? '');

    if ($website !== '') {
        $commentMessage = 'Komentar nije sačuvan.';
    } elseif ($name === '' || $text === '') {
        $commentMessage = 'Popuni ime i komentar.';
    } else {
        $key = 'blog_post_comment_' . (int) $post['id'];
        $last = (int) ($_SESSION[$key] ?? 0);
        if (time() - $last < 20) {
            $commentMessage = 'Sačekaj malo pre novog komentara.';
        } else {
            $safeName = mb_substr($name, 0, 120);
            $safeEmail = mb_substr($email, 0, 190);
            $safeText = mb_substr($text, 0, 2000);

            if ($commentsStorageMode === 'db') {
                $stmtCommentInsert = $conn->prepare('INSERT INTO post_comments_public (post_id, author_name, author_email, comment_text, status) VALUES (?, ?, ?, ?, "approved")');
                if ($stmtCommentInsert) {
                    $postIdForComment = (int) $post['id'];
                    $stmtCommentInsert->bind_param('isss', $postIdForComment, $safeName, $safeEmail, $safeText);
                    $stmtCommentInsert->execute();
                }
            } else {
                $all = file_exists($commentsFile) ? json_decode(file_get_contents($commentsFile), true) : [];
                if (!is_array($all)) {
                    $all = [];
                }
                $postKey = (string) ((int) $post['id']);
                if (!isset($all[$postKey]) || !is_array($all[$postKey])) {
                    $all[$postKey] = [];
                }
                $all[$postKey][] = [
                    'author_name' => $safeName,
                    'author_email' => $safeEmail,
                    'comment_text' => $safeText,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                file_put_contents($commentsFile, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            $_SESSION[$key] = time();
            $commentMessage = 'Komentar je objavljen.';
        }
    }
}

$postComments = [];
if ($commentsStorageMode === 'db') {
    $stmtCommentList = $conn->prepare('SELECT author_name, comment_text, created_at FROM post_comments_public WHERE post_id = ? AND status = "approved" ORDER BY created_at DESC LIMIT 25');
    if ($stmtCommentList) {
        $postIdForList = (int) $post['id'];
        $stmtCommentList->bind_param('i', $postIdForList);
        $stmtCommentList->execute();
        $resCommentList = $stmtCommentList->get_result();
        while ($row = $resCommentList->fetch_assoc()) {
            $postComments[] = $row;
        }
    }
} else {
    $all = file_exists($commentsFile) ? json_decode(file_get_contents($commentsFile), true) : [];
    if (is_array($all)) {
        $postComments = array_reverse($all[(string) ((int) $post['id'])] ?? []);
    }
}

$commentsFile = __DIR__ . '/../data/post_comments_fallback.json';
if ($commentsStorageMode === 'file' && !is_dir(dirname($commentsFile))) {
    mkdir(dirname($commentsFile), 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment_submit'])) {
    $name = trim($_POST['comment_name'] ?? '');
    $email = trim($_POST['comment_email'] ?? '');
    $text = trim($_POST['comment_text'] ?? '');
    $website = trim($_POST['website'] ?? '');

    if ($website !== '') {
        $commentMessage = 'Komentar nije sačuvan.';
    } elseif ($name === '' || $text === '') {
        $commentMessage = 'Popuni ime i komentar.';
    } else {
        $key = 'blog_post_comment_' . (int) $post['id'];
        $last = (int) ($_SESSION[$key] ?? 0);
        if (time() - $last < 20) {
            $commentMessage = 'Sačekaj malo pre novog komentara.';
        } else {
            $safeName = mb_substr($name, 0, 120);
            $safeEmail = mb_substr($email, 0, 190);
            $safeText = mb_substr($text, 0, 2000);

            if ($commentsStorageMode === 'db') {
                $stmtCommentInsert = $conn->prepare('INSERT INTO post_comments_public (post_id, author_name, author_email, comment_text, status) VALUES (?, ?, ?, ?, "approved")');
                if ($stmtCommentInsert) {
                    $postIdForComment = (int) $post['id'];
                    $stmtCommentInsert->bind_param('isss', $postIdForComment, $safeName, $safeEmail, $safeText);
                    $stmtCommentInsert->execute();
                }
            } else {
                $all = file_exists($commentsFile) ? json_decode(file_get_contents($commentsFile), true) : [];
                if (!is_array($all)) {
                    $all = [];
                }
                $postKey = (string) ((int) $post['id']);
                if (!isset($all[$postKey]) || !is_array($all[$postKey])) {
                    $all[$postKey] = [];
                }
                $all[$postKey][] = [
                    'author_name' => $safeName,
                    'author_email' => $safeEmail,
                    'comment_text' => $safeText,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                file_put_contents($commentsFile, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            $_SESSION[$key] = time();
            $commentMessage = 'Komentar je objavljen.';
        }
    }
}

$postComments = [];
if ($commentsStorageMode === 'db') {
    $stmtCommentList = $conn->prepare('SELECT author_name, comment_text, created_at FROM post_comments_public WHERE post_id = ? AND status = "approved" ORDER BY created_at DESC LIMIT 25');
    if ($stmtCommentList) {
        $postIdForList = (int) $post['id'];
        $stmtCommentList->bind_param('i', $postIdForList);
        $stmtCommentList->execute();
        $resCommentList = $stmtCommentList->get_result();
        while ($row = $resCommentList->fetch_assoc()) {
            $postComments[] = $row;
        }
    }
} else {
    $all = file_exists($commentsFile) ? json_decode(file_get_contents($commentsFile), true) : [];
    if (is_array($all)) {
        $postComments = array_reverse($all[(string) ((int) $post['id'])] ?? []);
    }
}

$postComments = [];
$stmtCommentList = $conn->prepare('SELECT author_name, comment_text, created_at FROM post_comments_public WHERE post_id = ? AND status = "approved" ORDER BY created_at DESC LIMIT 25');
$postIdForList = (int) $post['id'];
$stmtCommentList->bind_param('i', $postIdForList);
$stmtCommentList->execute();
$resCommentList = $stmtCommentList->get_result();
while ($row = $resCommentList->fetch_assoc()) {
    $postComments[] = $row;
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
<meta property="og:url" content="<?php echo htmlspecialchars(getBlogBaseUrl() . "/" . $categorySlug . "/" . $postSlug); ?>">
<meta name="twitter:card" content="summary_large_image">

    <script src="<?php echo htmlspecialchars(getBlogBasePath()); ?>/js/share.js"></script>
    <script src="<?php echo htmlspecialchars(getBlogBasePath()); ?>/js/main.js"></script>
         <style>
             footer {
               background-color: black;
             }
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
        
        
                    <?php if (!empty($relatedPosts)): ?>
                    <section style="margin-top:26px; border-top:1px solid #e5e7eb; padding-top:20px;">
                        <h2 style="font-size:1.3rem; margin-bottom:10px;">Pročitaj i ovo</h2>
                        <ul>
                            <?php foreach ($relatedPosts as $rp): ?>
                                <li><a href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/<?php echo htmlspecialchars($rp['category_slug']); ?>/<?php echo htmlspecialchars($rp['slug']); ?>"><?php echo htmlspecialchars($rp['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                    <?php endif; ?>

                    <section style="margin-top:30px; border-top:1px solid #e5e7eb; padding-top:20px;">
                        <h2 style="font-size:1.2rem; margin-bottom:10px;">Komentari</h2>
                        <?php if ($commentMessage): ?><p style="margin-bottom:10px;"><?php echo htmlspecialchars($commentMessage, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                        <?php foreach ($postComments as $pc): ?>
                            <div style="border:1px solid #e5e7eb; border-radius:8px; padding:10px; margin-bottom:8px;">
                                <strong><?php echo htmlspecialchars($pc['author_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <small style="color:#6b7280;"><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($pc['created_at']))); ?></small>
                                <p style="margin:6px 0 0;"><?php echo nl2br(htmlspecialchars($pc['comment_text'], ENT_QUOTES, 'UTF-8')); ?></p>
                            </div>
                        <?php endforeach; ?>

                        <form method="POST" style="display:grid; gap:8px; margin-top:10px;">
                            <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;">
                            <input type="text" name="comment_name" placeholder="Ime" required>
                            <input type="email" name="comment_email" placeholder="Email (opciono)">
                            <textarea name="comment_text" placeholder="Vaš komentar" rows="4" required></textarea>
                            <button class="btn btn-secondary" type="submit" name="post_comment_submit" value="1">Pošalji komentar</button>
                        </form>
                    </section>

                    <div class="col-5 col-sm-4"> <a href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/"
                            class="button-37 col-12  btn btn-secondary btn-lg" style="color:#fff !important; font-size:1rem; margin-top:20px">Nazad na blog</a></div>
    </article>

    <!-- Footer -->
     <?php include("../../komponente/cookie-banner.php"); ?>
    <?php include '../../komponente/footer.php'; ?>
    
<div class="floating-share-button">
    <button id="shareBtn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72" width="40" height="40" fill="none">
            <g style="stroke:white;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-width:3">
                <circle cx="50" cy="22" r="7"/> 
                <circle cx="22" cy="38" r="7"/> 
                <circle cx="50" cy="50" r="7"/> 
                <path d="m27 40 18 8"/> 
                <path d="m45 25-18 12"/> 
            </g>
        </svg>
    </button>
</div>






</body>
</html>
<?php $conn->close(); ?>

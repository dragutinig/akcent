<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'Database.php';


$requestUri = $_SERVER['REQUEST_URI'];

// Ekstrakcija "category" i "slug"
if (isset($_GET['category']) && isset($_GET['slug'])) {
    $categorySlug = $_GET['category'];
    $postSlug = $_GET['slug'];
} elseif (preg_match('#^/blog/([^/]+)/([^/]+)/?$#', $requestUri, $matches)) {
    $categorySlug = $matches[1];
    $postSlug = $matches[2];
} else {
    die("Invalid URL format.");
}

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Upit za postove
$postQuery = "
    SELECT posts.title, posts.content, posts.featured_image, posts.published_at, categories.name AS category_name,posts.meta_title,
           posts.meta_description, posts.slug AS post_slug, categories.slug AS category_slug
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    WHERE posts.slug = ? AND categories.slug = ? AND posts.status = 'published'";

$stmt = $conn->prepare($postQuery);
$stmt->bind_param('ss', $postSlug, $categorySlug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Post not found.");
}

$post = $result->fetch_assoc();

// Upit za tagove povezane sa postom
$tagsQuery = "
    SELECT tags.name
    FROM tags
    JOIN posttags ON tags.id = posttags.tag_id
    WHERE posttags.post_id = (SELECT id FROM posts WHERE slug = ?)";

$tagsStmt = $conn->prepare($tagsQuery);
$tagsStmt->bind_param('s', $postSlug);
$tagsStmt->execute();
$tagsResult = $tagsStmt->get_result();

$tags = [];
while ($tag = $tagsResult->fetch_assoc()) {
    $tags[] = $tag['name'];
}
$tagsString = implode(', ', $tags);


$relativePath = $post['featured_image'];
$absolutePath = str_replace("../", "https://akcent.rs/blog/", $relativePath);
?>







<!DOCTYPE html>
<html lang="sr">

<head>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <!-- Google tag (gtag.js) -->
     <script async src="https://www.googletagmanager.com/gtag/js?id=G-HDLXHWERJK"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <!-- Canonical Link -->
    <link rel="canonical" href="https://akcent.rs/blog/<?php echo htmlspecialchars($categorySlug); ?>/<?php echo htmlspecialchars($postSlug); ?>" />
    <!-- Meta Description -->
    <meta name="description" content="<?php echo htmlspecialchars($post['meta_description']); ?>">
    <!-- Meta Keywords (tags) -->
    <meta name="keywords" content="<?php echo htmlspecialchars($tagsString); ?>">
<meta property="og:title" content="<?php echo htmlspecialchars($post['meta_title']); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($post['meta_description']); ?>">
<meta property="og:image" content="<?php echo htmlspecialchars($absolutePath); ?>">
<meta property="og:url" content="<?php echo "https://akcent.rs/blog/" . $categorySlug . "/" . $postSlug; ?>">
<meta name="twitter:card" content="summary_large_image">

    <script src="../js/share.js"></script>
    <script src="../js/main.js"></script>
         <style>
             footer {
               background-color: black;
             }
         </style>

</head>

<body>
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Post Content -->
    <article>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="published-at"><?php echo date("F j, Y", strtotime($post['published_at'])); ?></p>
        <div class="post-content">
            <?php echo $post['content']; ?>
        </div>
        
        
                    <div class="col-5 col-sm-4"> <a href="https://akcent.rs/blog/"
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

<?php
// Zatvaranje konekcije
$conn->close();
?>


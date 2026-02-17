<?php
// Učitavanje klase Database
require_once 'Database.php';
require_once 'config.php';

// Kreiranje objekta baze i povezivanje
$db = new Database();
$conn = $db->connect();

// Provera da li je slug setovan u URL-u
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die("Invalid category.");
}

$category_slug = $conn->real_escape_string($_GET['slug']); // Uzmi slug iz URL-a

// SQL upit za dobijanje naziva kategorije
$categoryQuery = "SELECT name FROM categories WHERE slug = '$category_slug'";
$categoryResult = $conn->query($categoryQuery);

// Provera da li kategorija postoji
if ($categoryResult->num_rows == 0) {
    die("Category not found.");
}

$category = $categoryResult->fetch_assoc();
$category_name = $category['name'];

// SQL upit za dobijanje postova unutar kategorije
$postsQuery = "SELECT posts.id, posts.title, posts.slug, posts.content, posts.featured_image, posts.published_at, categories.slug AS category_slug
               FROM posts 
               JOIN categories ON posts.category_id = categories.id
               WHERE posts.status = 'published' AND categories.slug = '$category_slug' 
               ORDER BY posts.published_at DESC";

$posts = $conn->query($postsQuery);

// Provera rezultata upita
if (!$posts) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>


    
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HDLXHWERJK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HDLXHWERJK');
</script>

     <link rel="canonical" href="<?php echo htmlspecialchars(getBlogBaseUrl()); ?>/<?php echo $category_slug; ?>" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_name); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/post.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="<?php echo htmlspecialchars(getBlogBasePath()); ?>/js/main.js"></script>
        
       <style>
        footer {
    background-color: black;
}
        </style>
      
</head>

<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>


<!-- Posts Section -->
<section class="posts">
    <div class="container">
        <h1><?php echo htmlspecialchars($category_name); ?></h1>
        <div class="post-grid">
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <a href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/<?php echo $post['category_slug']; ?>/<?php echo $post['slug']; ?>"
                            class="post-link">
                            <div class="post-thumbnail-container">
                                <img src="<?php echo htmlspecialchars(resolveImageUrl($post['featured_image'])); ?>"
                                    alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <div class="badge-row">
                                    <span class="badge-item post-primary-category text-decoration-none">
                                        <?php echo ucfirst($post['category_slug']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <?php $excerpt = trim(strip_tags((string) $post['content'])); ?>
                                <p><?php echo htmlspecialchars($excerpt !== '' ? mb_substr($excerpt, 0, 180) : 'Pročitajte ceo tekst i saznajte više.'); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


    <!-- Include Footer -->
 <?php include("../../komponente/cookie-banner.php"); ?>
 <?php include '../../komponente/footer.php'; ?>

</body>

</html>

<?php
// Zatvaranje konekcije
$conn->close();
?>

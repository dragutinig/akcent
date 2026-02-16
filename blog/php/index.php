<?php
// Učitavanje klase Database
require_once 'Database.php';
require_once 'config.php';

// Kreiranje objekta baze i povezivanje
$db = new Database();
$conn = $db->connect();

// SQL upit za postove
$postsQuery = "SELECT posts.id, posts.title, posts.slug, posts.content, posts.featured_image, posts.published_at, categories.slug AS category_slug
               FROM posts 
               JOIN categories ON posts.category_id = categories.id
               WHERE posts.status = 'published' 
               ORDER BY posts.published_at DESC 
               LIMIT 10";

$posts = $conn->query($postsQuery);

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
    
    <title>Inspiracija, trendovi i prakticni saveti u enterijeru</title>
     <meta name="description" content="Saveti i inspiracija za uređenje doma – od izbora nameštaja do najnovijih trendova u enterijeru. Kreirajte funkcionalan prostor sa stilom">
     <link rel="canonical" href="<?php echo htmlspecialchars(getBlogBaseUrl()); ?>/" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="../js/main.js"></script>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/style.css">
      <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/post.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
       <style>
        footer {
    background-color: black;
}
        </style>
     
     <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "Akcent Blog",
  "url": "<?php echo htmlspecialchars(getBlogBaseUrl()); ?>/",
  "description": "Akcent Blog donosi savete, ideje i inspiraciju za uređenje doma – od izbora nameštaja do najnovijih trendova u enterijeru.",
  "publisher": {
    "@type": "Organization",
    "name": "Akcent Nameštaj",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/img/akcent-namestaj-logo.png"
    }
  }
}
</script>
   
        
</head>

<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>

    <!-- Posts Section -->
    <section class="posts">
    <div class="container">
        <h1 class="h1-index">Inspiracija, trendovi i praktični saveti u enterijeru</h1>
        <div class="post-grid">
            <?php while ($post = $posts->fetch_assoc()): ?>
            <div class="post">
                <a href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/<?php echo $post['category_slug']; ?>/<?php echo $post['slug']; ?>" class="post-link">
                    <div class="post-thumbnail-container">
                        <img src="<?php echo htmlspecialchars(resolveImageUrl($post['featured_image'])); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <div class="badge-row">
                            <span class="badge-item post-primary-category">
                                <?php echo ucfirst($post['category_slug']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="post-content">
                        <h2><?php echo $post['title']; ?></h2>
                        <p><?php echo strip_tags(substr($post['content'], 0, 300)); ?></p>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

 <?php include("../../komponente/cookie-banner.php"); ?>
    <!-- Include Footer -->
    <?php include '../../komponente/footer.php'; ?>

</body>

</html>

<?php
// Zatvaranje konekcije
$conn->close();
?>

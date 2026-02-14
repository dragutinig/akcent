<?php
require_once 'db_config.php';

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$searchQuery = "SELECT posts.id, posts.title, posts.slug, posts.content
                FROM posts
                WHERE posts.status = 'published' AND (
                    posts.title LIKE '%$searchTerm%' OR
                    posts.content LIKE '%$searchTerm%'
                ) ORDER BY posts.published_at DESC";

$searchResults = mysqli_query($conn, $searchQuery);
?>

<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretraga - Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Blog</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="#">O nama</a></li>
                <li><a href="#">Kontakt</a></li>
            </ul>
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Pretraga..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Pretraži</button>
            </form>
        </nav>
    </header>

    <main>
        <div class="posts">
            <h2>Rezultati Pretrage</h2>
            <?php if (mysqli_num_rows($searchResults) > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($searchResults)): ?>
                    <div class="post">
                        <h3><a href="post.php?slug=<?php echo $post['slug']; ?>"><?php echo $post['title']; ?></a></h3>
                        <p><?php echo substr($post['content'], 0, 200) . '...'; ?></p>
                        <p><a href="post.php?slug=<?php echo $post['slug']; ?>">Pročitaj više</a></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nema rezultata za vašu pretragu.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
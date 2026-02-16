<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->connect();

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchLike = '%' . $searchTerm . '%';

$searchQuery = "SELECT posts.id, posts.title, posts.slug, posts.content, categories.slug AS category_slug
                FROM posts
                JOIN categories ON posts.category_id = categories.id
                WHERE posts.status = 'published' AND (
                    posts.title LIKE ? OR
                    posts.content LIKE ?
                )
                ORDER BY posts.published_at DESC";

$stmt = $conn->prepare($searchQuery);
$stmt->bind_param('ss', $searchLike, $searchLike);
$stmt->execute();
$searchResults = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pretraga - Blog</title>
    <link rel="stylesheet" href="/blog/css/style.css">
</head>

<body>
    <header>
        <div class="logo">
            <h1>Blog</h1>
        </div>
        <nav>
            <ul>
                <li><a href="/blog/">Početna</a></li>
            </ul>
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Pretraga..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Pretraži</button>
            </form>
        </nav>
    </header>

    <main>
        <div class="posts">
            <h2>Rezultati pretrage</h2>
            <?php if ($searchResults->num_rows > 0): ?>
                <?php while ($post = $searchResults->fetch_assoc()): ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p><?php echo substr(strip_tags($post['content']), 0, 200) . '...'; ?></p>
                        <p><a href="/blog/<?php echo $post['category_slug']; ?>/<?php echo $post['slug']; ?>">Pročitaj više</a></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nema rezultata za vašu pretragu.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>

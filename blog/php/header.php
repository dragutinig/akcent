<?php
require_once 'Database.php';
require_once 'config.php';

$db = new Database();
$conn = $db->connect();
$siteBaseUrl = getSiteBaseUrl();
$blogBasePath = getBlogBasePath();

$categoriesQuery = "SELECT id, name, slug FROM categories ORDER BY name ASC";
$categories = $conn->query($categoriesQuery);

if (!$categories) {
    die("Query failed: " . $conn->error);
}
?>

<header class="main-header">
    <div class="container">
      <a id="logo" class="nazad" href="<?php echo htmlspecialchars($siteBaseUrl); ?>/">
        <img src="<?php echo htmlspecialchars($siteBaseUrl); ?>/img/akcent-namestaj-logo.png" alt="AKCENT Logo" style="max-height: 50px; background-color: black;">
    </a>
         <div class="logo">
            <p><a href="<?php echo htmlspecialchars($blogBasePath); ?>/">Akcent blog</a></p>
        </div>
     <nav>
    <a class="dropdown-toggle">
        <span class="menu-text">Kategorije</span>
        <span class="hamburger-icon" style="display: none;">&#9776;</span>
    </a>
    <ul class="dropdown-menu">
        <?php while ($category = $categories->fetch_assoc()): ?>
            <li>
                <a href="<?php echo htmlspecialchars($blogBasePath); ?>/<?php echo $category['slug']; ?>">
                    <?php echo $category['name']; ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</nav>


    </div>
</header>

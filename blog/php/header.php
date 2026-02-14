<?php
// Povezivanje sa bazom, učitavanje kategorija
require_once 'Database.php';

$db = new Database();
$conn = $db->connect();

// SQL upit za kategorije
$categoriesQuery = "SELECT id, name, slug FROM categories ORDER BY name ASC";
$categories = $conn->query($categoriesQuery);

if (!$categories) {
    die("Query failed: " . $conn->error);
}
?>

<header class="main-header">
    <div class="container">
      <a id="logo" class="nazad" href="https://akcent.rs/">
        <img src="https://akcent.rs/img/akcent-namestaj-logo.png" alt="AKCENT Logo" style="max-height: 50px; background-color: black;">
    </a>
         <!-- Centar: My Blog -->
        <div class="logo">
            <p><a href="/blog/php">Akcent blog</a></p>
        </div>
     <nav>
    <a class="dropdown-toggle">
        <span class="menu-text">Kategorije</span>
        <span class="hamburger-icon" style="display: none;">&#9776;</span>
    </a>
    <ul class="dropdown-menu">
        <?php while ($category = $categories->fetch_assoc()): ?>
            <li>
                <a href="/blog/<?php echo $category['slug']; ?>">
                    <?php echo $category['name']; ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</nav>

       
    </div>
</header>
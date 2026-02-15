 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' diff --git a/blog/php/categories.php
     b/blog/php/categories.php index 4f703fdce7ba29735b7c3c8da99233fada278fd6..91da6fd2df373f1c24cf1c848c58951975b1eaaa
     100644 --- a/blog/php/categories.php +++ b/blog/php/categories.php @@ -1,198 +1,121 @@ <?php
-// categories.php
-
-require_once 'Database.php'; // Pretpostavljamo da baza već postoji.
+require_once 'Database.php';
 session_start();
 
-// Proveri da li je korisnik prijavljen
-if (!isset($_SESSION['user_id'])) {
-    header("Location: ../html/login.html");
+if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
+    header('Location: ../html/reg.html');
     exit();
 }
 
-// Proveri da li je uloga korisnika 'admin'
-if ($_SESSION['role'] !== 'admin') {
-    echo "Pristup odbijen! Nemate administratorske privilegije.";
-    exit();
-}
+$db = (new Database())->connect();
 
-// Dodavanje nove kategorije
 if (isset($_POST['add_category'])) {
-    $name = $_POST['name'];
-    $slug = $_POST['slug'];
-
-    $db = (new Database())->connect();
-
-    $query = "INSERT INTO categories (name, slug) VALUES (?, ?)";
-    $stmt = $db->prepare($query);
+    $name = trim($_POST['name']);
+    $slug = trim($_POST['slug']);
+    $stmt = $db->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
     $stmt->bind_param('ss', $name, $slug);
     $stmt->execute();
-    header("Location: categories.php");
+    header('Location: categories.php');
     exit();
 }
 
-// Uređivanje kategorije
 if (isset($_POST['edit_category'])) {
-    $id = $_POST['id'];
-    $name = $_POST['name'];
-    $slug = $_POST['slug'];
-
-    $db = (new Database())->connect();
-
-    $query = "UPDATE categories SET name = ?, slug = ? WHERE id = ?";
-    $stmt = $db->prepare($query);
+    $id = (int)$_POST['id'];
+    $name = trim($_POST['name']);
+    $slug = trim($_POST['slug']);
+    $stmt = $db->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?');
     $stmt->bind_param('ssi', $name, $slug, $id);
     $stmt->execute();
-    header("Location: categories.php");
+    header('Location: categories.php');
     exit();
 }
 
-// Brisanje kategorije
 if (isset($_GET['delete'])) {
-    $id = $_GET['delete'];
-
-    $db = (new Database())->connect();
-
-    $query = "DELETE FROM categories WHERE id = ?";
-    $stmt = $db->prepare($query);
+    $id = (int)$_GET['delete'];
+    $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
     $stmt->bind_param('i', $id);
     $stmt->execute();
-    header("Location: categories.php");
+    header('Location: categories.php');
     exit();
 }
 
-// Prikaz lista kategorija
-function listCategories($db)
-{
-    $query = "SELECT id, name, slug FROM categories";
-    $result = $db->query($query);
-
-    echo "<h2>Kategorije</h2>";
-    echo "<a href='#addCategory' class='btn btn-success mb-3' data-toggle='modal'>Dodaj novu kategoriju</a>";
-    echo "<table class='table table-striped'>";
-    echo "<tr><th>ID</th><th>Ime</th><th>Slug</th><th>Akcija</th></tr>";
-
-    while ($category = $result->fetch_assoc()) {
-        echo "<tr><td>{$category['id']}</td><td>{$category['name']}</td><td>{$category['slug']}</td>";
-        echo "<td>
-                <a href='#editCategory{$category['id']}' class='btn btn-primary btn-sm' data-toggle='modal'>Izmeni</a>
-                <a href='categories.php?delete={$category['id']}' class='btn btn-danger btn-sm'>Obriši</a>
-              </td></tr>";
-    }
-
-    echo "</table>";
-}
-
-// Forma za dodavanje nove kategorije
-function addCategoryForm()
-{
-    echo "<div class='modal fade' id='addCategory' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
-            <div class='modal-dialog' role='document'>
-                <div class='modal-content'>
-                    <div class='modal-header'>
-                        <h5 class='modal-title' id='exampleModalLabel'>Dodaj novu kategoriju</h5>
-                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
-                            <span aria-hidden='true'>&times;</span>
-                        </button>
-                    </div>
-                    <form action='categories.php' method='POST'>
-                        <div class='modal-body'>
-                            <div class='form-group'>
-                                <label for='name'>Ime kategorije</label>
-                                <input type='text' name='name' id='name' class='form-control' required>
-                            </div>
-                            <div class='form-group'>
-                                <label for='slug'>Slug</label>
-                                <input type='text' name='slug' id='slug' class='form-control' required>
-                            </div>
-                        </div>
-                        <div class='modal-footer'>
-                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zatvori</button>
-                            <button type='submit' name='add_category' class='btn btn-primary'>Dodaj</button>
-                        </div>
-                    </form>
-                </div>
-            </div>
-        </div>";
-}
-
-// Forma za izmenu kategorije
-function editCategoryForm($category)
-{
-    echo "<div class='modal fade' id='editCategory{$category['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
-            <div class='modal-dialog' role='document'>
-                <div class='modal-content'>
-                    <div class='modal-header'>
-                        <h5 class='modal-title' id='exampleModalLabel'>Izmeni kategoriju</h5>
-                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
-                            <span aria-hidden='true'>&times;</span>
-                        </button>
-                    </div>
-                    <form action='categories.php' method='POST'>
-                        <div class='modal-body'>
-                            <input type='hidden' name='id' value='{$category['id']}'>
-                            <div class='form-group'>
-                                <label for='name'>Ime kategorije</label>
-                                <input type='text' name='name' id='name' class='form-control' value='{$category['name']}' required>
-                            </div>
-                            <div class='form-group'>
-                                <label for='slug'>Slug</label>
-                                <input type='text' name='slug' id='slug' class='form-control' value='{$category['slug']}' required>
-                            </div>
-                        </div>
-                        <div class='modal-footer'>
-                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zatvori</button>
-                            <button type='submit' name='edit_category' class='btn btn-primary'>Sačuvaj</button>
-                        </div>
-                    </form>
-                </div>
-            </div>
-        </div>";
-}
-
+$categories = $db->query('SELECT id, name, slug FROM categories ORDER BY id DESC');
 ?> - <!DOCTYPE html>
     -<html lang="en">
     -
     +<html lang="sr-RS">

     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         - <title>Blog Admin Dashboard - Kategorije</title>
         -
         <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
         + <title>Kategorije | Akcent Blog Admin</title>
         +
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
         +
         <link rel="stylesheet" href="/blog/css/admin.css">
     </head>
     +

     <body class="admin-body">
         +<div class="admin-shell">
             + <div class="admin-topbar">
                 + <div>
                     + <h1 class="h4 mb-1">Kategorije</h1>
                     + <p class="text-muted mb-0">Upravljanje blog kategorijama</p>
                     + </div>
                 + <div class="admin-actions">
                     + <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Nazad na dashboard</a>
                     + <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                         data-bs-target="#addCategoryModal">+ Dodaj kategoriju</button>
                     + </div>
                 + </div>

             -

             <body>
                 - <div class="container mt-5">
                     - <h1>Dobrodošli na admin panel</h1>
                     -
                     <hr>
                     -
                     - <?php
-        $database = new Database();
-        $db = $database->connect();
-
-        // Dodavanje forme za kategorije
-        addCategoryForm();
-
-        // Prikaz kategorija
-        listCategories($db);
-        ?>
                     -
                     -
                     <!-- Za editovanje kategorija -->
                     - <?php
-        $query = "SELECT id, name, slug FROM categories";
-        $result = $db->query($query);
-
-        while ($category = $result->fetch_assoc()) {
-            editCategoryForm($category);
-        }
-        ?>
                     -
                     + <div class="admin-card">
                         + <div class="table-responsive">
                             + <table class="table table-striped table-hover align-middle">
                                 + <thead>
                                     <tr>
                                         <th>ID</th>
                                         <th>Naziv</th>
                                         <th>Slug</th>
                                         <th>Akcije</th>
                                     </tr>
                                 </thead>
                                 + <tbody>
                                     + <?php while ($category = $categories->fetch_assoc()): ?>
                                     + <tr>
                                         + <td><?= (int)$category['id']; ?></td>
                                         + <td><?= htmlspecialchars($category['name']); ?></td>
                                         + <td><code><?= htmlspecialchars($category['slug']); ?></code></td>
                                         + <td class="admin-actions">
                                             + <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                                 data-bs-target="#editModal<?= (int)$category['id']; ?>">Izmeni</button>
                                             + <a class="btn btn-outline-danger btn-sm"
                                                 href="categories.php?delete=<?= (int)$category['id']; ?>"
                                                 onclick="return confirm('Obriši kategoriju?');">Obriši</a>
                                             + </td>
                                         + </tr>
                                     + <?php endwhile; ?>
                                     + </tbody>
                                 + </table>
                             + </div>
                     </div>
                     +
                 </div>
                 +
                 +<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
                     + <div class="modal-dialog">
                         <div class="modal-content">
                             + <form method="POST" action="categories.php">
                                 + <div class="modal-header">
                                     <h5 class="modal-title">Dodaj novu kategoriju</h5><button type="button"
                                         class="btn-close" data-bs-dismiss="modal"></button>
                                 </div>
                                 + <div class="modal-body">
                                     + <div class="mb-3"><label class="form-label">Naziv</label><input type="text"
                                             name="name" class="form-control" required></div>
                                     + <div class="mb-3"><label class="form-label">Slug</label><input type="text"
                                             name="slug" class="form-control" pattern="[a-z0-9-]+" required></div>
                                     + </div>
                                 + <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                         data-bs-dismiss="modal">Zatvori</button><button type="submit"
                                         name="add_category" class="btn btn-primary">Sačuvaj</button></div>
                                 + </form>
                             + </div>
                     </div>
                     +</div>

                 - <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                 - <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
                 - <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                 +<?php
+$categories = $db->query('SELECT id, name, slug FROM categories ORDER BY id DESC');
+while ($category = $categories->fetch_assoc()): ?>
                 +<div class="modal fade" id="editModal<?= (int)$category['id']; ?>" tabindex="-1" aria-hidden="true">
                     + <div class="modal-dialog">
                         <div class="modal-content">
                             + <form method="POST" action="categories.php">
                                 + <input type="hidden" name="id" value="<?= (int)$category['id']; ?>">
                                 + <div class="modal-header">
                                     <h5 class="modal-title">Izmena kategorije</h5><button type="button"
                                         class="btn-close" data-bs-dismiss="modal"></button>
                                 </div>
                                 + <div class="modal-body">
                                     + <div class="mb-3"><label class="form-label">Naziv</label><input type="text"
                                             name="name" class="form-control"
                                             value="<?= htmlspecialchars($category['name']); ?>" required></div>
                                     + <div class="mb-3"><label class="form-label">Slug</label><input type="text"
                                             name="slug" class="form-control" pattern="[a-z0-9-]+"
                                             value="<?= htmlspecialchars($category['slug']); ?>" required></div>
                                     + </div>
                                 + <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                         data-bs-dismiss="modal">Zatvori</button><button type="submit"
                                         name="edit_category" class="btn btn-primary">Sačuvaj</button></div>
                                 + </form>
                             + </div>
                     </div>
                     +</div>
                 +<?php endwhile; ?>
                 +
                 +<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
             </body>
             -
             -

     </html>
     \ No newline at end of file
     +

     </html>

     EOF
     )
<?php
// categories.php

require_once 'Database.php'; // Pretpostavljamo da baza već postoji.
session_start();

// Proveri da li je korisnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}

// Proveri da li je uloga korisnika 'admin'
if ($_SESSION['role'] !== 'admin') {
    echo "Pristup odbijen! Nemate administratorske privilegije.";
    exit();
}

// Dodavanje nove kategorije
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    $db = (new Database())->connect();

    $query = "INSERT INTO categories (name, slug) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $name, $slug);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

// Uređivanje kategorije
if (isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    $db = (new Database())->connect();

    $query = "UPDATE categories SET name = ?, slug = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssi', $name, $slug, $id);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

// Brisanje kategorije
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $db = (new Database())->connect();

    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

// Prikaz lista kategorija
function listCategories($db)
{
    $query = "SELECT id, name, slug FROM categories";
    $result = $db->query($query);

    echo "<h2>Kategorije</h2>";
    echo "<a href='#addCategory' class='btn btn-success mb-3' data-toggle='modal'>Dodaj novu kategoriju</a>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>ID</th><th>Ime</th><th>Slug</th><th>Akcija</th></tr>";

    while ($category = $result->fetch_assoc()) {
        echo "<tr><td>{$category['id']}</td><td>{$category['name']}</td><td>{$category['slug']}</td>";
        echo "<td>
                <a href='#editCategory{$category['id']}' class='btn btn-primary btn-sm' data-toggle='modal'>Izmeni</a>
                <a href='categories.php?delete={$category['id']}' class='btn btn-danger btn-sm'>Obriši</a>
              </td></tr>";
    }

    echo "</table>";
}

// Forma za dodavanje nove kategorije
function addCategoryForm()
{
    echo "<div class='modal fade' id='addCategory' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Dodaj novu kategoriju</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <form action='categories.php' method='POST'>
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='name'>Ime kategorije</label>
                                <input type='text' name='name' id='name' class='form-control' required>
                            </div>
                            <div class='form-group'>
                                <label for='slug'>Slug</label>
                                <input type='text' name='slug' id='slug' class='form-control' required>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zatvori</button>
                            <button type='submit' name='add_category' class='btn btn-primary'>Dodaj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>";
}

// Forma za izmenu kategorije
function editCategoryForm($category)
{
    echo "<div class='modal fade' id='editCategory{$category['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLabel'>Izmeni kategoriju</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <form action='categories.php' method='POST'>
                        <div class='modal-body'>
                            <input type='hidden' name='id' value='{$category['id']}'>
                            <div class='form-group'>
                                <label for='name'>Ime kategorije</label>
                                <input type='text' name='name' id='name' class='form-control' value='{$category['name']}' required>
                            </div>
                            <div class='form-group'>
                                <label for='slug'>Slug</label>
                                <input type='text' name='slug' id='slug' class='form-control' value='{$category['slug']}' required>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zatvori</button>
                            <button type='submit' name='edit_category' class='btn btn-primary'>Sačuvaj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Admin Dashboard - Kategorije</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Dobrodošli na admin panel</h1>
        <hr>

        <?php
        $database = new Database();
        $db = $database->connect();

        // Dodavanje forme za kategorije
        addCategoryForm();

        // Prikaz kategorija
        listCategories($db);
        ?>

        <!-- Za editovanje kategorija -->
        <?php
        $query = "SELECT id, name, slug FROM categories";
        $result = $db->query($query);

        while ($category = $result->fetch_assoc()) {
            editCategoryForm($category);
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
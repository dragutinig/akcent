<?php






error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pre nego što se pošalju bilo kakvi podaci korisniku, pozivamo session_start()
session_start();

require_once 'Database.php';

// Proveri da li je korisnik admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/login.html");
    exit();
}

// Funkcija za generisanje validnog sluga
function generate_slug($post_url)
{
    // Pretvara razmake u crtice
    $slug = strtolower(str_replace(' ', '-', $post_url));

    // Uklanja sve karaktere koji nisu slova, brojevi, ili crtica
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug);

    // Ako slug sadrži specijalne karaktere, kodira ih
    $slug = urlencode($slug);

    return $slug;
}

// Povezivanje sa bazom
$database = new Database();
$db = $database->connect();

// Preuzimanje postojećeg posta prema ID-u
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // SQL upit za preuzimanje podataka postojećeg posta
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    // Ako post ne postoji, preusmeri na listu postova
    if (!$post) {
        header("Location: dashboard.php?error=Post ne postoji");
        exit();
    }

    // Preuzimanje tagova postojećeg posta
    $tag_query = "SELECT t.name FROM tags t
                  JOIN posttags pt ON t.id = pt.tag_id
                  WHERE pt.post_id = ?";
    $tag_stmt = $db->prepare($tag_query);
    $tag_stmt->bind_param('i', $post_id);
    $tag_stmt->execute();
    $tags_result = $tag_stmt->get_result();
    $tags = [];
    while ($row = $tags_result->fetch_assoc()) {
        $tags[] = $row['name'];
    }
    $tags_string = implode(', ', $tags);
} else {
    header("Location: dashboard.php?error=Post ID nije prosleđen");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preuzimanje podataka iz forme
    $title = trim($_POST['title']);
    $post_url = trim($_POST['url']); // URL -> post_url
    $content = trim($_POST['content']);
    $slug = generate_slug($post_url); // Koristi funkciju za generisanje sluga
    $status = $_POST['status'];
    $category_id = $_POST['category_id'] ?? null;
    $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;
    $tags = array_map('trim', explode(',', $_POST['tags'])); // Preuzimanje tagova i razdvajanje po zarezima
    $meta_description = trim($_POST['meta_description']); // Meta description
    $meta_title = trim($_POST['meta_title']); // Meta title
    

    // Upload "istaknute slike" ako je nova
    $featured_image = $post['featured_image'];
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';

        // Proveri da li folder postoji, ako ne, kreiraj ga
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = basename($_FILES['featured_image']['name']);
        $target_file = $upload_dir . $image_name;

        // Provera ekstenzije fajla radi bezbednosti
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            echo "<div class='alert alert-danger'>Dozvoljeni formati su: JPG, JPEG, PNG, GIF.</div>";
            exit();
        }

        // Premesti fajl u folder "uploads/"
        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
            $featured_image = $target_file;
        } else {
            echo "<div class='alert alert-danger'>Greška pri uploadu 'istaknute slike'.</div>";
            exit();
        }
    }

    // SQL upit za ažuriranje postojećeg posta
    $query = "UPDATE posts SET title = ?, slug = ?, content = ?, featured_image = ?, user_id = ?, category_id = ?, status = ?, published_at = ?, updated_at = NOW(), meta_description = ?, meta_title = ?, post_url = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssssissssssi', $title, $slug, $content, $featured_image, $_SESSION['user_id'], $category_id, $status, $published_at, $meta_description, $meta_title, $post_url, $post_id);

    if ($stmt->execute()) {
        // Brisanje postojećih tagova
        $delete_tags_query = "DELETE FROM posttags WHERE post_id = ?";
        $delete_tags_stmt = $db->prepare($delete_tags_query);
        $delete_tags_stmt->bind_param('i', $post_id);
        $delete_tags_stmt->execute();

        // Obrada novih tagova
        foreach ($tags as $tag_name) {
            if (!empty($tag_name)) {
                // Provera da li tag vec postoji
                $tag_query = "SELECT id FROM tags WHERE name = ?";
                $tag_stmt = $db->prepare($tag_query);
                $tag_stmt->bind_param('s', $tag_name);
                $tag_stmt->execute();
                $tag_result = $tag_stmt->get_result();

                if ($tag_result->num_rows > 0) {
                    // Tag vec postoji, preuzmi ID
                    $tag_id = $tag_result->fetch_assoc()['id'];
                } else {
                    // Tag ne postoji, dodaj ga
                    $slug = generate_slug($tag_name); // Generiši slug za tag
                    $insert_tag_query = "INSERT INTO tags (name, slug, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
                    $insert_tag_stmt = $db->prepare($insert_tag_query);
                    $insert_tag_stmt->bind_param('ss', $tag_name, $slug);
                    $insert_tag_stmt->execute();
                    $tag_id = $insert_tag_stmt->insert_id;
                }

                // Povezivanje posta i taga
                $post_tag_query = "INSERT INTO posttags (post_id, tag_id) VALUES (?, ?)";
                $post_tag_stmt = $db->prepare($post_tag_query);
                $post_tag_stmt->bind_param('ii', $post_id, $tag_id);
                $post_tag_stmt->execute();
            }
        }

        header("Location: dashboard.php?message=Post uspešno ažuriran!");
        exit();
    } else {
        echo "Greška pri ažuriranju posta: " . $db->error;
    }
}

// Preuzimanje kategorija za dropdown
$query = "SELECT * FROM categories";
$result = $db->query($query);
$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
 <script src="../js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: false,
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media',
                'searchreplace', 'table', 'visualblocks', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat | load_template_ultimateguide load_template_studijaslucaja load_template_kakoda load_template_listnipost', // Dodata četiri dugmeta za učitavanje template-a

            // Funkcija za učitavanje HTML template-a
            setup: function(editor) {
                // Dodajemo dugme za svaki template
                editor.ui.registry.addButton('load_template_ultimateguide', {
                    text: 'Učitaj Ultimate Guide Template',
                    onAction: function() {
                        fetch('../html/ultimateguide.html') // Putanja do Ultimate Guide template-a
                            .then(response => response.text())
                            .then(data => {
                                editor.setContent(data);
                            })
                            .catch(error => {
                                alert('Greška pri učitavanju template-a: ' + error);
                            });
                    }
                });

                editor.ui.registry.addButton('load_template_studijaslucaja', {
                    text: 'Učitaj Studija Slučaja Template',
                    onAction: function() {
                        fetch(
                                '../html/studijaslucaja.html'
                            ) // Putanja do Studija Slučaja template-a
                            .then(response => response.text())
                            .then(data => {
                                editor.setContent(data);
                            })
                            .catch(error => {
                                alert('Greška pri učitavanju template-a: ' + error);
                            });
                    }
                });

                editor.ui.registry.addButton('load_template_kakoda', {
                    text: 'Učitaj Kako Da Template',
                    onAction: function() {
                        fetch('../html/kakoda.html') // Putanja do Kako Da template-a
                            .then(response => response.text())
                            .then(data => {
                                editor.setContent(data);
                            })
                            .catch(error => {
                                alert('Greška pri učitavanju template-a: ' + error);
                            });
                    }
                });

                editor.ui.registry.addButton('load_template_listnipost', {
                    text: 'Učitaj Listni Post Template',
                    onAction: function() {
                        fetch('../html/listnipost.html') // Putanja do Listni Post template-a
                            .then(response => response.text())
                            .then(data => {
                                editor.setContent(data);
                            })
                            .catch(error => {
                                alert('Greška pri učitavanju template-a: ' + error);
                            });
                    }
                });
            },

            // Konfiguracija za upload slike
            images_upload_url: 'upload_image.php',
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function() {
                        var file = this.files[0];
                        var formData = new FormData();
                        formData.append('file', file);

                        // AJAX za upload slike
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'upload_image.php');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                var json = JSON.parse(xhr.responseText);
                                if (json.location) {
                                    callback(json.location); // URL slike
                                } else {
                                    alert('Greška pri uploadu slike.');
                                }
                            }
                        };
                        xhr.send(formData);
                    };

                    input.click();
                }
            }
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Uredi Post</h1>
        <form action="edit_post.php?post_id=<?= $post_id ?>" method="POST" enctype="multipart/form-data" id="postForm">
            <div class="form-group">
                <label for="title">Naslov</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= $post['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Sadržaj</label>
                <textarea name="content" id="content" class="form-control"><?= $post['content']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="meta_title">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="<?= $post['meta_title']; ?>">
            </div>
            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="form-control"><?= $post['meta_description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Kategorija</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Izaberite kategoriju</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>" <?= $category['id'] == $post['category_id'] ? 'selected' : ''; ?>><?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tags">Tagovi (odvojeni zarezom)</label>
                <input type="text" name="tags" id="tags" class="form-control" value="<?= $tags_string; ?>">
            </div>
            <div class="form-group">
                <label for="url">URL</label>
                <input type="text" name="url" id="url" class="form-control" value="<?= $post['post_url']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="draft" <?= $post['status'] == 'draft' ? 'selected' : ''; ?>>S draft</option>
                    <option value="published" <?= $post['status'] == 'published' ? 'selected' : ''; ?>>Objavljeno</option>
                </select>
            </div>
            <div class="form-group">
                <label for="featured_image">Istaknuta slika</label>
                <input type="file" name="featured_image" id="featured_image" class="form-control">
                <?php if ($post['featured_image']): ?>
                    <img src="<?= $post['featured_image']; ?>" alt="Istaknuta slika" class="img-fluid mt-2">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Ažuriraj post</button>
        </form>
    </div>
</body>
</html>

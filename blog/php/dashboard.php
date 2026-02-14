<?php
require_once 'Database.php';

session_start();

// Postavi maksimalno vreme neaktivnosti (6 sat = 21600 sekundi)
$inactive = 21600;

// Proveri kada je korisnik poslednji put bio aktivan
if (isset($_SESSION['last_activity'])) {
    $session_life = time() - $_SESSION['last_activity'];
    if ($session_life > $inactive) {
        // Uništi sesiju ako je prošlo više od 1 sat
        session_unset();
        session_destroy();
        header("Location: /blog/html/reg.html");
        exit();
    }
}

// Ažuriraj vreme poslednje aktivnosti
$_SESSION['last_activity'] = time();

// Proveri da li je korisnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header("Location: /blog/html/reg.html");
    exit();
}

// Proveri da li je uloga korisnika 'admin'
if ($_SESSION['role'] !== 'admin') {
    echo "Pristup odbijen! Nemate administratorske privilegije.";
    exit();
}

// Funkcija za prikaz liste korisnika
function listUsers($db)
{
    $query = "SELECT id, username, email, role, created_at FROM users";
    $result = $db->query($query);

    echo "<h2>Korisnici</h2>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>ID</th><th>Korisničko ime</th><th>Email</th><th>Rola</th><th>Datum registracije</th><th>Akcija</th></tr>";

    while ($user = $result->fetch_assoc()) {
        echo "<tr><td>{$user['id']}</td><td>{$user['username']}</td><td>{$user['email']}</td>";
        echo "<td>{$user['role']}</td><td>{$user['created_at']}</td>";
        echo "<td>
            <a href='edit_user.php?id={$user['id']}' class='btn btn-sm btn-primary'>Izmeni</a> 
            <a href='delete_user.php?id={$user['id']}' class='btn btn-sm btn-danger'>Obriši</a>
        </td></tr>";
    }
    echo "</table>";
}

// Funkcija za prikaz liste postova
function listPosts($db)
{
    $query = "SELECT posts.id, posts.title, posts.slug, posts.status, posts.created_at, users.username AS creator 
              FROM posts 
              JOIN users ON posts.user_id = users.id";
    $result = $db->query($query);
    
    

    echo "<h2>Postovi</h2>";
    echo "<a href='create-post.php' class='btn btn-success mb-3'>Dodaj novi post</a>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>ID</th><th>Naslov</th><th>Slug</th><th>Status</th><th>Kreator</th><th>Datum kreiranja</th><th>Akcije</th></tr>";

    while ($post = $result->fetch_assoc()) {
   
        echo "<tr>";
        echo "<td>{$post['id']}</td>";
        echo "<td>{$post['title']}</td>";
        echo "<td>{$post['slug']}</td>";
        echo "<td>{$post['status']}</td>";
        echo "<td>{$post['creator']}</td>";
        echo "<td>{$post['created_at']}</td>";
        echo "<td>
        
        


               <a href='edit_post.php?post_id={$post['id']}' class='btn btn-primary btn-sm'>Izmeni</a>
                <a href='#' class='btn btn-danger btn-sm delete-post-button' data-toggle='modal' data-target='#deleteModal' data-id='{$post['id']}'>Obriši</a>
            </td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Funkcija za prikaz liste komentara
function listComments($db)
{
    $query = "SELECT c.id, c.content, c.status, c.created_at, u.username AS user, p.title AS post 
              FROM comments c 
              JOIN users u ON c.user_id = u.id 
              JOIN posts p ON c.post_id = p.id";
    $result = $db->query($query);

    echo "<h2>Komentari</h2>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>ID</th><th>Korisnik</th><th>Post</th><th>Sadržaj</th><th>Status</th><th>Datum kreiranja</th><th>Akcije</th></tr>";

    while ($comment = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$comment['id']}</td>";
        echo "<td>{$comment['user']}</td>";
        echo "<td>{$comment['post']}</td>";
        echo "<td>{$comment['content']}</td>";
        echo "<td>{$comment['status']}</td>";
        echo "<td>{$comment['created_at']}</td>";
        echo "<td>
                <a href='approve_comment.php?id={$comment['id']}' class='btn btn-success btn-sm'>Odobri</a>
                <a href='reject_comment.php?id={$comment['id']}' class='btn btn-warning btn-sm'>Odbij</a>
                <a href='delete_comment.php?id={$comment['id']}' class='btn btn-danger btn-sm'>Obriši</a>
            </td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Dobrodošli na administratorski panel, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
            <!-- Dugme za logout -->
            <a href="logout.php" class="btn btn-danger">Odjavi se</a>
        </div>
        <hr>

        <?php
        $database = new Database();
        $db = $database->connect();

        echo "<nav class='nav nav-tabs'>";
        echo "<a class='nav-item nav-link active' href='#users'>Korisnici</a>";
        echo "<a class='nav-item nav-link' href='#posts'>Postovi</a>";
        echo "<a class='nav-item nav-link' href='#comments'>Komentari</a>";
        echo "<a class='nav-item nav-link' href='categories.php'>Kategorije</a>";
        echo "</nav>";

        echo "<div id='users' class='mt-4'>";
        listUsers($db);
        echo "</div>";

        echo "<div id='posts' class='mt-4'>";
        listPosts($db);
        echo "</div>";

        echo "<div id='comments' class='mt-4'>";
        listComments($db);
        echo "</div>";
        ?>
    </div>

    <!-- Modal za potvrdu brisanja posta -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Potvrdi brisanje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Da li ste sigurni da želite da obrišete ovaj post?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Odustani</button>
                    <a href="#" id="deletePostBtn" class="btn btn-danger">Obriši</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Kada korisnik klikne na dugme za brisanje posta
        $(".delete-post-button").on("click", function() {
            var postId = $(this).data("id"); // Preuzmi ID posta
            var deleteUrl = "delete_post.php?id=" + postId; // URL za brisanje posta

            // Postavi URL za brisanje u dugme za potvrdu u modalu
            $("#deletePostBtn").attr("href", deleteUrl);
        });
    </script>
</body>

</html>

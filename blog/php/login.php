<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Database.php';
require_once 'User.php';

if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin') {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $user->email = htmlspecialchars(strip_tags($_POST['email'] ?? ''));
    $password = htmlspecialchars(strip_tags($_POST['password'] ?? ''));

    if ($user->emailExists()) {
        $user->password_hash = $password;
        if ($user->login()) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['last_activity'] = time();

            header('Location: dashboard.php');
            exit();
        }
    }

    $error = 'Email ili lozinka nisu tačni.';
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin prijava | Akcent Blog</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <main class="admin-wrap" style="max-width:640px; margin-top:80px;">
        <section class="topbar" style="display:block;">
            <h1>Akcent Blog Admin</h1>
            <p class="muted" style="margin-top:6px;">Prijavi se za upravljanje postovima, kategorijama i komentarima.</p>
        </section>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <section class="section">
            <div class="section-header"><h2>Prijava</h2></div>
            <div style="padding:14px;">
                <form method="POST" class="form-grid">
                    <div class="form-group full">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group full">
                        <label for="password">Lozinka</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group full" style="display:flex; flex-direction:row; justify-content:space-between; align-items:center;">
                        <button class="btn btn-primary" type="submit">Prijavi se</button>
                        <a href="forgot_password.php">Zaboravljena lozinka?</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'Database.php';
require_once 'User.php';
require_once 'config.php';

start_secure_session();

$attemptsFile = __DIR__ . '/../data/login_attempts.json';
if (!is_dir(dirname($attemptsFile))) {
    mkdir(dirname($attemptsFile), 0777, true);
}

function login_get_ip()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
}

function login_attempt_state($attemptsFile, $ip)
{
    $raw = file_exists($attemptsFile) ? json_decode(file_get_contents($attemptsFile), true) : [];
    if (!is_array($raw)) {
        $raw = [];
    }

    $now = time();
    foreach ($raw as $key => $row) {
        if (!isset($row['expires_at']) || (int) $row['expires_at'] < $now) {
            unset($raw[$key]);
        }
    }

    if (!isset($raw[$ip])) {
        $raw[$ip] = ['count' => 0, 'blocked_until' => 0, 'expires_at' => $now + 86400];
    }

    return [$raw, $raw[$ip]];
}

function login_attempt_save($attemptsFile, $all)
{
    file_put_contents($attemptsFile, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$forceLogin = isset($_GET['force']) && $_GET['force'] === '1';
if ($forceLogin) {
    session_unset();
    session_destroy();
    start_secure_session();
}

if (!$forceLogin && isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin') {
    header('Location: ' . getBlogBasePath() . '/php/dashboard.php');
    exit();
}

$error = '';
$ip = login_get_ip();
list($attempts, $attemptState) = login_attempt_state($attemptsFile, $ip);

if (!empty($attemptState['blocked_until']) && time() < (int) $attemptState['blocked_until']) {
    $remaining = (int) $attemptState['blocked_until'] - time();
    $error = 'Previše pokušaja prijave. Pokušaj ponovo za ' . max(1, (int) ceil($remaining / 60)) . ' min.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error === '') {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $user->email = htmlspecialchars(strip_tags($_POST['email'] ?? ''));
    $password = htmlspecialchars(strip_tags($_POST['password'] ?? ''));

    if ($user->emailExists()) {
        $user->password_hash = $password;
        if ($user->login()) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['last_activity'] = time();
            $_SESSION['fingerprint'] = hash('sha256', login_get_ip() . '|' . ($_SERVER['HTTP_USER_AGENT'] ?? ''));

            $attempts[$ip] = ['count' => 0, 'blocked_until' => 0, 'expires_at' => time() + 86400];
            login_attempt_save($attemptsFile, $attempts);

            header('Location: ' . getBlogBasePath() . '/php/dashboard.php');
            exit();
        }
    }

    $attempts[$ip]['count'] = (int) ($attempts[$ip]['count'] ?? 0) + 1;
    $attempts[$ip]['expires_at'] = time() + 86400;
    if ($attempts[$ip]['count'] >= 5) {
        $attempts[$ip]['blocked_until'] = time() + 900;
        $attempts[$ip]['count'] = 0;
    }
    login_attempt_save($attemptsFile, $attempts);

    $error = 'Email ili lozinka nisu tačni.';
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin prijava | Akcent Blog</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/css/admin.css">
</head>
<body>
    <main class="login-wrap">
        <nav class="login-top-nav" aria-label="Brza navigacija">
            <a class="btn btn-secondary" href="<?php echo htmlspecialchars(getSiteBaseUrl()); ?>/">Nazad na sajt</a>
            <a class="btn btn-info" href="<?php echo htmlspecialchars(getBlogBasePath()); ?>/">Otvori blog</a>
        </nav>
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
                <form method="POST" class="form-grid" autocomplete="off">
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

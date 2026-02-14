<?php
require_once 'Database.php';
require_once 'User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = htmlspecialchars(strip_tags($_POST['token']));
    $new_password = htmlspecialchars(strip_tags($_POST['password']));
    $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));

    if ($new_password !== $confirm_password) {
        echo "Lozinke se ne poklapaju.";
        exit();
    }

    $database = new Database();
    $db = $database->connect();

    // Provera tokena u bazi
    $query = "SELECT email FROM users WHERE reset_token = ? AND reset_expire_at > NOW()";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();

    if ($email) {
        // Resetovanje lozinke
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET password_hash = ?, reset_token = NULL, reset_expire_at = NULL WHERE email = ?";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bind_param("ss", $hashed_password, $email);
        $update_stmt->execute();

        echo "Lozinka je uspešno resetovana. Možete se sada prijaviti.";
    } else {
        echo "Link za resetovanje lozinke je nevažeći ili je istekao.";
    }
}
?>

<!-- Forma za resetovanje lozinke -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow">
        <h2 class="text-center">Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter new password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                    placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>
    </div>
</body>

</html>
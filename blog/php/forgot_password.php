<?php
require_once 'Database.php';
require_once 'User.php';
// Includujemo PHPMailer klase
require_once '../PHPMailer/src/PHPMailer.php';    // Putanja do PHPMailer.php fajla
require_once '../PHPMailer/src/SMTP.php';          // Putanja do SMTP.php fajla
require_once '../PHPMailer/src/Exception.php';     // Putanja do Exception.php fajla

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(strip_tags($_POST['email']));

    // Povezivanje sa bazom
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $email;

    if ($user->emailExists()) {
        // Generišemo jedinstveni token za resetovanje lozinke
        $token = bin2hex(random_bytes(32));
        $expire_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token važi sat vremena

        // Sačuvaj token u bazi
        $query = "UPDATE users SET reset_token = ?, reset_expire_at = ? WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $token, $expire_at, $email);
        $stmt->execute();

        // Generišemo link za resetovanje lozinke
        $reset_link = "http://localhost/php/reset_password.php?token=" . $token;

        // Pošaljite email sa linkom za resetovanje lozinke
        $mail = new PHPMailer(true); // Kreiramo instancu PHPMailer-a
        try {
            // Postavke SMTP servera
            $mail->isSMTP();                                            // Koristi SMTP
            $mail->Host       = 'smtp.gmail.com';                         // Gmail SMTP server
            $mail->SMTPAuth   = true;                                     // Omogući SMTP autentifikaciju
            $mail->Username   = 'ignjatovicdragutin92@gmail.com';                  // Tvoj Gmail nalog
            $mail->Password   = 'Dragigagi1janja';                   // Tvoja Gmail lozinka
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Početni TLS enkripcija
            $mail->Port       = 587;                                     // Port 587 za SMTP sa TLS

            // Postavke email-a
            $mail->setFrom('ignjatovicdragutin92@gmail.com', 'Dragutin');  // Tvoja email adresa
            $mail->addAddress($email);                            // Email primaoca

            // Sadržaj email-a
            $mail->isHTML(true);                                  // HTML format
            $mail->Subject = 'Reset Password';                    // Predmet email-a
            $mail->Body    = 'Kliknite na sledeći link da resetujete lozinku: <a href="' . $reset_link . '">' . $reset_link . '</a>';

            // Pokušaj da pošalješ email
            $mail->send();
            echo 'Instrukcije za resetovanje lozinke su poslate na vaš email.';
        } catch (Exception $e) {
            // Ako dođe do greške, prikazujemo poruku
            echo "Došlo je do greške prilikom slanja email-a. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Korisnik sa ovom email adresom ne postoji.";
    }
}

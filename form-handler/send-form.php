<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ BITNO: charset utf-8 da JSON ne "poludi" sa kvačicama
header('Content-Type: application/json; charset=utf-8');

require __DIR__.'/PHPMailer.php';
require __DIR__.'/SMTP.php';
require __DIR__.'/Exception.php';

$config = require __DIR__.'/config.php';

function out($ok, $msg) {
  echo json_encode(
    ['ok' => $ok, 'message' => $msg],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
  );
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(false, 'Neispravan zahtev');

// ✅ mali helper za čitanje POST-a
function post($key, $default = '') {
  return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

// ✅ uzmi polja i očisti ih (da se ne ubaci HTML u mail)
$name       = post('name');
$phone      = post('phone');
$email      = post('email');
$location   = post('location');
$type       = post('type');
$dimensions = post('dimensions');
$notes      = post('notes');

$nameSafe       = strip_tags($name);
$phoneSafe      = strip_tags($phone);
$emailSafe      = filter_var($email, FILTER_SANITIZE_EMAIL);
$locationSafe   = strip_tags($location);
$typeSafe       = strip_tags($type);
$dimensionsSafe = strip_tags($dimensions);
$notesSafe      = strip_tags($notes);

// ✅ backend validacija (obavezno, iako radiš i na frontu)
if ($nameSafe === '' || $phoneSafe === '' || $emailSafe === '' || $locationSafe === '' || $typeSafe === '') {
  out(false, 'Molimo popunite sva obavezna polja.');
}
if (!filter_var($emailSafe, FILTER_VALIDATE_EMAIL)) {
  out(false, 'Email adresa nije ispravna.');
}

// ✅ Elektronski potpis (HTML koji si poslao)
$signatureHtml = <<<HTML
<div style="font-family: Arial, sans-serif; color: #333333; font-size: 14px; line-height: 1.5;">
  <p style="margin: 0;">Srdačan pozdrav,</p>
  <p style="margin: 0; font-weight: bold; font-size: 15px; color: #000000;">
    Dragutin Ignjatović
  </p>
  <p style="margin: 0;">
    Tel:
    <a href="tel:0616485508" style="color: #1a73e8; text-decoration: none;">
      0616485508
    </a>
  </p>
  <p style="margin: 0;">
    <a href="https://www.akcent.rs" target="_blank" style="color: #1a73e8; text-decoration: none;">
      www.akcent.rs
    </a>
  </p>
  <hr style="margin: 10px 0; border: none; border-top: 1px solid #dddddd;">
  <!-- Logo (aktiviraj kasnije)
  <img src="https://www.namestajdrvo.com/images/logo.png"
       alt="Logo"
       style="width: 120px; height: auto; margin-top: 5px;">
  -->
</div>
HTML;

$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host       = $config['smtp_host'];
  $mail->SMTPAuth   = true;
  $mail->Username   = $config['smtp_user'];
  $mail->Password   = $config['smtp_pass'];
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port       = $config['smtp_port'];

  // ✅ UTF-8 + encoding za “Nameštaj”
  $mail->CharSet  = 'UTF-8';
  $mail->Encoding = 'base64';

  // ✅ “Akcent Nameštaj” će sada normalno izlaziti u headeru
  $mail->setFrom($config['smtp_user'], 'Akcent Nameštaj');
  $mail->addAddress($config['to_email']);

  // Reply-To samo ako email validan
  $mail->addReplyTo($emailSafe, $nameSafe);

  $mail->Subject = 'Novi upit sa sajta';

  // Admin mail može plain text (lakše za čitanje)
  $mail->isHTML(false);
  $mail->Body =
    "Ime: {$nameSafe}\n" .
    "Telefon: {$phoneSafe}\n" .
    "Email: {$emailSafe}\n" .
    "Lokacija: {$locationSafe}\n" .
    "Tip: {$typeSafe}\n" .
    "Opis: {$dimensionsSafe}\n" .
    "Napomena: {$notesSafe}\n";

  // Attachment (ograniči malo — sigurnije)
  if (!empty($_FILES['attachment']['tmp_name']) && is_uploaded_file($_FILES['attachment']['tmp_name'])) {
    // (opciono) limit veličine npr. 8MB
    if (!empty($_FILES['attachment']['size']) && $_FILES['attachment']['size'] > 8 * 1024 * 1024) {
      out(false, 'Fajl je prevelik (maks 8MB).');
    }
    $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
  }

  $mail->send();

  /* ✅ Auto-reply korisniku + HTML potpis */
  if (!empty($config['send_autoreply'])) {
    $reply = new PHPMailer(true);
    $reply->isSMTP();
    $reply->Host       = $config['smtp_host'];
    $reply->SMTPAuth   = true;
    $reply->Username   = $config['smtp_user'];
    $reply->Password   = $config['smtp_pass'];
    $reply->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $reply->Port       = $config['smtp_port'];

    // ✅ UTF-8 i OVDE (ovo ti je često uzrok “kvačica” u autoreply-u)
    $reply->CharSet  = 'UTF-8';
    $reply->Encoding = 'base64';

    $reply->setFrom($config['smtp_user'], 'Akcent Nameštaj');
    $reply->addAddress($emailSafe, $nameSafe);
    $reply->Subject = 'Primili smo vaš upit';

    $reply->isHTML(true);
    $reply->Body = "
      <div style='font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6;'>
        <p>Hvala na interesovanju. Primili smo vaš upit i javljamo se uskoro.</p>
        {$signatureHtml}
      </div>
    ";
    $reply->AltBody = "Hvala na interesovanju. Primili smo vaš upit i javljamo se uskoro.\n\nAkcent Namestaj\nwww.akcent.rs";

    $reply->send();
  }

  out(true, 'Upit je uspešno poslat.');

} catch (Exception $e) {
  out(false, 'Greška pri slanju emaila.');
}

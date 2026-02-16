<?php
http_response_code(403);
header('Content-Type: text/plain; charset=UTF-8');
echo 'Registracija korisnika je onemogućena. Korisnike dodajte ručno u bazi.';

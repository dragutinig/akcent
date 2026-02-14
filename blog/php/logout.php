<?php
session_start();

// Uništavamo sesiju
session_unset();
session_destroy();

// Preusmeravamo korisnika na login stranicu
header("Location: /blog/html/reg.html");
exit();

<!doctype html>
<html lang="sr">

<head>
    
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16983581852"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16983581852');
  gtag('config', 'G-HDLXHWERJK'); // Google Analytics 4

</script>
    
    
    <meta charset="utf-8">
    <title>Najlepše kuhinje: Slike kuhinja za inspiraciju</title>
    <meta name="description" content="Otkrijte najlepše kuhinje kroz inspirativne slike modernih, klasičnih i luksuznih kuhinjskih rešenja. 
    Pronađite savršen dizajn za vaš dom.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://akcent.rs/najlepse-slike-kuhinja.php" />

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/kuhinje.css">
    <link rel="stylesheet" href="css/sekcijaTriSlike.css">




    <meta name="theme-color" content="#fafafa">
</head>

<body>

    <main>

        <?php include("komponente/nav.php"); ?>


        <div style="height:50px;"></div>


        <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <header class="row header mb-5">
                    <h1 class="main_h3 col-10">
                     Najlepše kuhinje – Slike za inspiraciju </h1>

                </header>
                <div class="row">



                    <p class="col-9 paragrafStyle"> U potrazi ste za idealnom kuhinjom? 
                    Bilo da volite moderne enterijere, klasičnu eleganciju ili luksuzne detalje, 
                    prava inspiracija može napraviti ključnu razliku. 
                    Pogledajte našu pažljivo odabranu kolekciju slika najlepših kuhinja i pronađite stil koji odgovara vašem prostoru.</p>
                </div>
              
            </article>
        </section>
        
        
        <section class="materijal  shadow-sm  shadow-sm  rounded">
            <article class="container ">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Kako odabrati savršenu kuhinju za vaš dom?</h2>

                </header>
                <div class="row">

                    <p class="col-9 paragrafStyle">
                         &#9989; <b> &nbsp Prostor i funkcionalnost </b>– Planirajte kuhinju prema raspoloživom prostoru i vašim potrebama. Ukoliko je manji prostor za kuhinju, savetujemo da pokušate maksimalno da iskoristite prostor. 
                         Obratite pažnju da imate dovoljno prostora za odlaganje.
                        <br>&#9989; <b> &nbsp Materijali i završne obrade </b>– Birajte između visokokvalitetne iverice, MDF-a, masivnog drveta i drugih pomoćnih materijala (staklo, keramika...). Informišite se o vrstama radnih ploča 
                        (pogledajte naš <a href="https://akcent.rs/blog/kupovina-namestaja/kako-izabrati-materijal-za-kuhinje-po-meri"
                            target="_blank" style="color:blue">vodič za biranje kuhinjske radne ploče)</a>
                        <br>&#9989; <b> &nbsp Održavanje i dugotrajnost </b>– Birajte površine koje su otporne na vlagu, ogrebotine i lako se čiste.<br>
                    </p>
                </div>
            </article>
        </section>
        
        
        
         <section class="projektovanje color section2   shadow-sm  rounded">

            <article class="container">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Najpopularniji stilovi kuhinja: Najlepše kuhinje u 2025.</h2>
                </header>

                <div class="row">
                    <p class="col-9 paragrafStyle">&#128313;<strong>&nbsp Moderne kuhinje</strong> – Minimalistički dizajn, ravne linije, moderno osvetljenje (LED trake, senzori). Ugradni kuhinjski elementi, i kombinacije materijala poput MDF-a, stakla i nerđajućeg čelika.</p>
                    <p class="col-9 paragrafStyle">&#128313;<strong>&nbsp Rustične kuhinje</strong> – Tonovi drveta, otvorene police i retro detalji za prijatan ambijent. Frontovi sa ukopanim šarama koji upotpunjavaju izgled.  </p>
                    <p class="col-9 paragrafStyle">&#128313;<strong>&nbsp Luksuzne kuhinje</strong> – Mermer, visokokvalitetni lakirani elementi i elegantni detalji. Često se koriste detalji u zlatnoj ili boji mesinga.</p>
                    <p class="col-9 paragrafStyle">&#128313;<strong>&nbsp Skandinavski stil</strong> – Svetle boje, prirodni materijali i jednostavnost za prostran i osvežen izgled. Stil koji je popularan već godinama zbog svog čistog i toplog izgleda. U poslednje vreme, sve je popularniji i <b>Japandi</b> stil. </p>

                </div>
               
            </article>
        </section>
        
        <section class="projektovanje color section2   shadow-sm  rounded">

            <article class="container">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     MODERNE KUHINJE</h2>
                </header>
                <div class="row">
                    <?php
                    $files = scandir('img/najlepseKuhinjeSlike-moderne/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/najlepseKuhinjeSlike-moderne/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>

                </div>
               
            </article>
        </section>
        
        <section class="projektovanje color section2   shadow-sm  rounded">

            <article class="container">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     RUSTIČNE KUHINJE</h2>
                </header>
                <div class="row">
                    <?php
                    $files = scandir('img/najlepseKuhinjeSlike-rusticne/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/najlepseKuhinjeSlike-rusticne/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>

                </div>
               
            </article>
        </section>
        
        
        <section class="projektovanje color section2   shadow-sm  rounded">

            <article class="container">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     LUKSUZNE KUHINJE</h2>
                </header>
                <div class="row">
                    <?php
                    $files = scandir('img/najlepseKuhinjeSlike-luksuzne/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/najlepseKuhinjeSlike-luksuzne/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>

                </div>
               
            </article>
        </section>
        
        
             <section class="materijal  shadow-sm  shadow-sm  rounded">
            <article class="container ">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10 text-info">Kuhinje po meri - Kontaktirajte nas</h2>

                </header>
                <div class="row">

                    <p class="col-9 paragrafStyle"><b>Bilo da se nalazite u Beogradu, Pančevu ili okolini, 
                    kontaktirajte nas kako bismo zajedno kreirali kuhinju koja savršeno odgovara vašim potrebama.</b>
                    </p>
                    <p class="col-9 paragrafStyle">&#128222; <strong>Pozovite nas:</strong> +381616485508 </p>
                    <p class="col-9 paragrafStyle">&#128231; <strong>Pišite nam:</strong> akcentnamestaj@gmail.com</p>
                    <p class="col-9 paragrafStyle">&#128205; <strong>Zakažite sastanak ili izlazak na teren:</strong> Pančevo / Beograd / Zemun</p>
                     <div class="row mt-5">
                        <div class="col-sm-4 col-10 buttonDiv">
                            <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                                onclick="window.location.href='kontakt.php'">Kontakt
                            </button>
                        </div>
                    </div>



                </div>
            </article>
        </section>
        



        
        
              





      



        

<?php include("komponente/cookie-banner.php"); ?>
        <?php include("komponente/button-top.php"); ?>

        <?php include("komponente/footer.php"); ?>


    </main>















    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="js/vendor/modernizr-3.8.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-3.4.1.min.js"><\/script>')
    </script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
    <script>
    window.ga = function() {
        ga.q.push(arguments)
    };
    ga.q = [];
    ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto');
    ga('set', 'transport', 'beacon');
    ga('send', 'pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>
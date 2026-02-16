<!doctype html>
<html class="no-js" lang="">

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
    <title>Akcent Namestaj - O nama </title>
    <meta name="description" content="Celokupan proces za kuhinje po meri: 3D projektovanje, izrada i montaža na jednom mestu. 
Kvalitetna izrada, sa najboljim okovima. Bez skrivenih troškova.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="favicon.ico">
    <!-- Place favicon.ico in the root directory -->
    
     <link rel="canonical" href="https://akcent.rs/onama.php" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/kuhinje.css">
    <link rel="stylesheet" href="css/lead-form.css">




    <meta name="theme-color" content="#fafafa">
</head>

<body>

    <main>

        <?php include("komponente/nav.php"); ?>


        <div style="height:50px;"></div>


        <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h1 class="main_h3 col-10">
                        O nama:
                    </h1>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle">Dobrodošli u Akcent nameštaj, čast nam je da vam predstavimo naše proizvode i usluge.
                    </p>

                    <p class="col-9 paragrafStyle">Nudimo nameštaj po meri, sa fokusom na moderne kuhinje po meri, i američke plakare, ali i druge proizvode poput kupatilskih elemenata, tv komoda, itd. 
                   <br> Osim pojedinačnih proizvoda koje možete poručiti od nas, bavimo se i većim projektima. Ukoliko imate arhitektu ili dizajnera sa kojim sarađujete, mi možemo ponuditi usluge izrade i montaže.</p>

                    <p class="col-9 paragrafStyle">Smatramo da je transparentnost najvažnija u ovom poslu, i zato garantujemo jasne cene bez skrivenih troškova. <br>
                    Čim odradimo merenja, i dobijemo 3d projekte, mi formiramo cenu sa jasnim informacijama kako bi svaki klijent imao precizan uvid u to što kupuje.
                    </p>

                    <p class="col-9 paragrafStyle">Hvala vam što ste deo naše priče.</p>


                </div>



                <div class="row mt-5">
                    <div class="col-sm-4 col-10 buttonDiv">
                        <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                            onclick="window.location.href='cene.php'">CENE
                        </button>
                    </div>
                </div>



            </article>
        </section>






        <?php include("komponente/zastoMi.php"); ?>

        <?php include("komponente/kakoDoNas.php"); ?>

        <section class="porucivanje   shadow-sm ">
            <article class="container">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">Najcešća pitanja </h2>

                </div>
                <div class=" row">
                    <?php include("komponente/pitanjaKuhinje.php"); ?>
                </div>

            </article>
        </section>

        <section class="">

            <article class="container">

                <h3 class="galerija">Pogledajte neke nase radove!</h3>
                <div class="row">


                    <?php
                    $files = scandir('img/3dprojektovanjeGalerija/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/3dprojektovanjeGalerija/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>

                </div>

                <p class="paragrafStyle galerijaTekst"> Pogledajte vise slika u
                    galeriji</p>
                <div class="row mt-5">
                    <div class="col-sm-4 col-10 buttonDiv">
                        <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                            onclick="window.location.href='namestaj-po-meri-galerija.php'">Galerija
                        </button>
                    </div>
                </div>
            </article>
        </section>
<?php include("komponente/lead-form.php"); ?>
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
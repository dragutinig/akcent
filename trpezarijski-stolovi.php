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
    <title>Trpezarijski stolovi od masiva – Dizajnerski modeli</title>
    <meta name="description" content="Trpezarijski stolovi po meri od masivnog drveta. Dizajnerski modeli vrhunskog kvaliteta – Beograd i Pančevo. Katalog uskoro!">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://akcent.rs/trpezarijski-stolovi.php" />

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="favicon.ico">
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
        <link rel="stylesheet" href="css/lead-form.css">

    <meta name="theme-color" content="#fafafa">
    
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Trpezarijski stolovi po meri",
  "image": "https://akcent.rs/img/trpezarijskiStoloviGalerija/trpezarijski-stolovi-po-meri.webp",
  "description": "Trpezarijski stolovi po meri od masiva i najkvalitetnijih materijala. Izrada dizajnerskih modela po vašim željama i potrebama.",
  "brand": {
    "@type": "Brand",
    "name": "Akcent"
  },
  "offers": {
    "@type": "Offer",
    "url": "https://akcent.rs/trpezarijski-stolovi.php",
    "priceCurrency": "RSD",
    "price": "30000",
    "availability": "https://schema.org/PreOrder",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>

    
    
</head>

<body>

    <main>

        <?php include("komponente/nav.php"); ?>


        <div style="height:50px;"></div>

        <section class="section section1 shadow-sm  rounded" style="background-image: url(../img/pozadine/trpezarijski-stolovi-beograd-pozadina.webp);">
            <article class="container vertical">
                <header class="row align-self-start  ">
                    <h1 class="main_h1 col-10">Trpezarijski stolovi po meri: <br> Dizajnerski modeli od masivnog drveta</h1>

                </header>
                <div class=" row align-self-center">

                    <p class="col-sm-8 col-10 paragrafStyle">Trpezarijski stolovi nisu samo funkcionalan komad nameštaja – 
                    oni su središte svakodnevnog života. Okupljaju porodicu, stvaraju atmosferu topline i doma, i određuju estetiku celog prostora. 
                    Ako tražite sto koji spaja lepotu, dugovečnost i vrhunsku izradu – na pravom ste mestu. 
                </div>


                <div class="brzoBiranje row ">

                    <div class="col-5 col-sm-4"> <a href=""
                            class="button-37 col-12  btn btn-dark btn-lg ">KATALOG - uskoro!</a></div>
                    <div class="col-5 col-sm-4"> <a href="klub-stolovi.php"
                            class="button-37 col-12  btn btn-warning btn-lg ">Klub stolovi</a></div>
                </div>
            </article>
        </section>


        <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Dizajnerski modeli inspirisani svetskim trendovima </h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle"> U AKCENT-u izrađujemo trpezarijske stolove po meri, sa posebnim fokusom na <b>dizajnerske modele od masivnog drveta.</b>  <br>
                    U ponudi su najpoznatiji modeli trpezarijskih stolova sa svetskog tržišta – komadi koji prilače pažnju i koji su godinama na među najpopularnijima.<br>
                    Trenutno razvijamo i katalog dizajnerskih trpezarijskih stolova, u kom ćete moći da pronađete gotove modele sa varijantama dimenzija, materijala i završne obrade.<br><br>
                        &#128227; <b> &nbsp Katalog izlazi uskoro! </b>
                    </p>
                </div>

                <div class="row mt-5">
                    <div class="col-sm-4 col-10 buttonDiv">
                        <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                            onclick="window.location.href='kontakt.php'">Kontaktirajte nas
                        </button>
                    </div>
                </div>
            </article>
        </section>
        
        <section class="">
            <article class="container">
                <div class="row">
                    <?php
                    $files = scandir('img/trpezarijskiStoloviGalerija/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/trpezarijskiStoloviGalerija/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>
                </div>
            </article>
        </section>
        
        
         <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Trpezarijski stolovi od masiva - kvalitet koji traje</h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle">Naši trpezarijski stolovi izrađeni su većinski od masiva. Bilo da volite hrast, orah, jasen ili drugo autohtono drvo, 
                    svaki sto dolazi sa izuzetnom stabilnošću, trajnošću i prirodnom estetikom.
                    </p>
                </div>

                <div class="row mt-5">
                    <p class="col-9 paragrafStyle">Prednosti masivnog drveta:
                    <br>&#9989; <b> &nbsp Prirodna lepota i unikatna struktura </b>
                    <br>&#9989; <b> &nbsp Dug vek trajanja (trpezarijski stolovi koji traju generacijama) </b>
                    <br>&#9989; <b> &nbsp Mogućnost reparacije i osvežavanja </b>
                    <br>&#9989; <b> &nbsp Otpornost na habanje i deformacije </b>

                    </p>
                </div>
            </article>
        </section>
        
        
        <section class="projektovanje    shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Šta imamo u ponudi </h2>
                </div>
               <div class="row mt-5">
                    <p class="col-9 paragrafStyle">
                    <br>&#9989; <b> &nbsp Pravougaone i ovalne trpezarijske stolove, kao i stolove nepravilnih oblika </b>
                    <br>&#9989; <b> &nbsp Stolove za 4, 6, 8 i više osoba </b>
                    <br>&#9989; <b> &nbsp Fiksne i stolove na razvlačenje </b>
                    <br>&#9989; <b> &nbsp Kombinacije drveta i stakla/keramike/metala </b>

                    </p>
                </div>
            </article>
        </section>
        
    
        
             <section class="color shadow-sm">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10 text-info">Trpezarijski stolovi po meri - Pančevo, Beograd, Zemun</h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle"><b>Bilo da se nalazite u Beogradu, Zemunu, Pančevu ili okolini, 
                    kontaktirajte nas kako bismo zajedno došli do modela koji savršeno odgovara vašim potrebama.</b>
                    </p>
                     <div class="row mt-5">
                        <div class="col-sm-4 col-10 buttonDiv">
                            <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                                onclick="window.location.href='kontakt.php'">Kontaktirajte nas
                            </button>
                        </div>
                    </div>

                </div>
            </article>
        </section>

     <section class="projektovanje    shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">
                    &#128176; Pristupačne cene za premium proizvod </h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle">Cena trpezarijskog stola zavisi od dimenzija, vrste drveta i završne obrade. 
                    Ipak, zahvaljujući sopstvenoj proizvodnji i pažljivoj nabavci materijala, 
                    garantujemo cene prilagođene našem tržištu za dizajnerske stolove vrhunskog kvaliteta.
                    </p>
                </div>
            </article>
        </section>

    <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <div class="row">
                    
                    <p class="col-9 paragrafStyle">Naši trpezarijski stolovi po meri predstavljaju spoj svetskog dizajna, 
                    tradicionalne izrade i prirodnih materijala. 
                    Ako tražite sto koji neće biti samo komad nameštaja, već iskustvo za sva čula – kontaktirajte nas već danas.
                    </p>
                    <div class="col-sm-4 col-10 buttonDiv">
                            <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                                onclick="window.location.href='kontakt.php'">Kontaktirajte nas
                            </button>
                        </div>
                    
                </div>
            </article>
        </section>
        
      <?php include("komponente/lead-form.php"); ?>

        
   

        <?php include("komponente/zastoMi.php"); ?>



        <?php include("komponente/kakoDoNas.php"); ?>






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
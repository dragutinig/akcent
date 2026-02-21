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
    <title>Klub stolovi - Nameštaj po meri Pančevo, Beograd, Zemun</title>
    <meta name="description" content="Klub stolovi i drugi nameštaj po meri po meri za moderan enterijer. Kvalitetni materijali i završna obrada. Pristupačne i transparentne cene.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://akcent.rs/klub-stolovi.php" />

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

    <?php include("komponente/seo.php"); ?>

</head>

<body>

    <main>

        <?php include("komponente/nav.php"); ?>


        <div style="height:50px;"></div>

        <section class="section section1 shadow-sm  rounded" style="background-image: url(../img/pozadine/klub-stolovi-pozadina.webp);">
            <article class="container vertical">
                <div class="row align-self-start  ">
                    <h1 class="main_h1 col-10">Klub stolovi – <br> Za enterijer sa Pinteresta!</h1>

                </div>
                <div class=" row align-self-center">

                    <p class="col-sm-8 col-10 paragrafStyle">Klub stolovi su neizostavan deo svakog dnevnog boravka. Njihova namena nije samo estetska, već i praktična, i svi ih koristimo svakodnevno.
                    Bilo da preferirate minimalistički dizajn, klasične drvene modele ili luksuzne varijante sa staklenim i metalnim detaljima, pravi klub sto će upotpuniti estetiku vaše dnevne sobe.
</p>

                </div>


                <div class="brzoBiranje row ">

                    <div class="col-5 col-sm-4"> <a href="namestaj-za-dnevnu-sobu-po-meri.php"
                            class="button-37 col-12  btn btn-warning btn-lg ">Dnevna soba</a></div>
                    <div class="col-5 col-sm-4"> <a href="materijali.php"
                            class="button-37 col-12  btn btn-warning btn-lg ">Materijali</a></div>
                </div>
            </article>
        </section>


        <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Klub stolovi po meri – Pravi akcentni komadi </h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle">U AKCENT-u izrađujemo klub stolove po meri, prilagođene vašim željama. <br>
                    Prednosti izrade po meri uključuju:
                    	<br><br>&#9989; <b> &nbsp Mogućnost biranja dimenzija </b>– Idealno rešenje za male i velike prostore.
                        <br>&#9989; <b> &nbsp Širok izbor materijala i boja </b>– Prilagodite dizajn postojećem enterijeru.
                        <br>&#9989; <b> &nbsp Mogućnost biranja svih elemenata </b>– Dodatne fioke, police ili skriveni prostori za skladištenje.
                        <br>&#9989; <b> &nbsp Visok kvalitet izrade </b>– Koristimo samo najbolje materijale i okove za dugotrajnost i stabilnost.
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
                    $files = scandir('img/klubStoloviGalerija/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/klubStoloviGalerija/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>

                </div>
             <div class="row mt-5">
                    <div class="col-sm-4 col-10 buttonDiv">
                        <button type="button" class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                            onclick="window.location.href='namestaj-po-meri-galerija.php'"> Galerija &#8594;
                        </button>
                    </div>
                </div>
               
               
            </article>
        </section>
        
        
             <section class="color shadow-sm">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10 text-info">Klub stolovi po meri - Pančevo, Beograd, Zemun</h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle"><b>Bilo da se nalazite u Beogradu, Zemunu, Pančevu ili okolini, 
                    kontaktirajte nas kako bismo zajedno došli do modela koja savršeno odgovara vašim potrebama.</b>
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
                     Zašto su klub stolovi važan element u enterijeru? </h2>

                </div>
                <div class="row">

                    <p class="col-9 paragrafStyle">Klub stolovi su više od običnog komada nameštaja – oni kombinuju stil i funkcionalnost, 
                    doprinoseći celokupnom izgledu dnevne sobe. Njihova svrha može biti višestruka:
                    	<br><br>&#9989; <b> &nbsp Dekorativni element - estetika </b>– Klub sto može biti upečatljiv detalj koji unosi sofisticiranost u prostor.
                        <br>&#9989; <b> &nbsp Funkcionalna površina </b>– Idealno mesto za odlaganje časopisa, daljinskih upravljača, šoljica kafe i drugih sitnica.
                        <br>&#9989; <b> &nbsp Skladišni prostor  </b>– Mnogi modeli dolaze sa fiokama ili policama za dodatno odlaganje.
                    </p>
                </div>
            </article>
        </section>

    <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <div class="row header mb-5">
                    <h2 class="main_h3 col-10">
                     Saveti: Kako izabrati idealan klub sto? </h2>

                </div>
                <div class="row">
                    
                    <p class="col-9 paragrafStyle">Prilikom odabira klub stola, važno je uzeti u obzir nekoliko ključnih faktora:</p>
                    <h3 class="main_h3 col-10"> Dimenzije i oblik </h3>
                    <p class="col-9 paragrafStyle">
                    	<br>&#9679; <b> &nbsp Manji prostori </b>– Preporučuju se okrugli ili ovalni klub stolovi koji vizuelno povećavaju prostor.
                        <br>&#9679; <b> &nbsp Veće dnevne sobe </b>– Pravougaoni ili kvadratni modeli za maksimalnu praktičnost.
                        <br>&#9679; <b> &nbsp Dva manja stola umesto jednog velikog  </b>– Fleksibilno rešenje za prilagodljiv enterijer. Takođe prilično zgodno za veće prostorije.
                    </p>
                    
                    <h3 class="main_h3 col-10"> Materijal izrade </h3>
                    <p class="col-9 paragrafStyle">
                    	<br>&#9679; <b> &nbsp Drvo </b>– Klasičan i dugotrajan izbor koji unosi toplinu u prostor.
                        <br>&#9679; <b> &nbsp MDF i iverica </b>– Savremene varijante sa širokim spektrom završnih obrada i boja.
                        <br>&#9679; <b> &nbsp Kombinovani materijali </b>– Staklo i metal ili drvo i beton daju jedinstven vizuelni efekat.
                    </p>
                    
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
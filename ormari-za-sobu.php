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
    <title>Ormari za sobu po meri - Kombinacija stila i funkcionalnosti</title>
    <meta name="description" content="Ormari za sobu po meri, Pančevo i Beograd.
    Kvalitetna izrada sa najboljim okovima, bez skrivenih troškova.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="favicon.ico">
    <!-- Place favicon.ico in the root directory -->
    
    <link rel="canonical" href="https://akcent.rs/ormari-za-sobu.php" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/sekcijaTriSlike.css">
    <link rel="stylesheet" href="css/plakari.css">



    <meta name="theme-color" content="#fafafa">
</head>

<body>

    <main>

        <?php include("komponente/nav.php"); ?>


        <div style="height:50px;"></div>

        <section class="section section1 section1Plakari  shadow-sm  rounded">
            <article class="container vertical">
                <header class="row align-self-start  ">
                    <h1 class="main_h1 col-10">Ormari za sobu po meri:<br>
                        Kvalitet i dobre cene</h1>




                </header>
                <div class=" row align-self-center">

                    <p class="podnaslov col-sm-8 col-10 paragrafStyle"> Ormari za sobu su, osim funkcionalnosti, važan estetski element u opremanju spavaće sobe. 
                    Mali, veliki, ugradni ormari - Mi u AKCENT-u kreiramo ormare po meri koji se savršeno uklapaju u vaš životni prostor. <br>
                        Naše usluge obuhvataju: 3D projektovanje, izradu i montažu ormara.
                        Sa nama, cene su uvek jasne i transparentne, bez skrivenih troškova.</p>

                </div>


                <div class="brzoBiranje row ">
                    <div class="col-5 col-sm-4"><a href="cene.php"
                            class="button-37 col-12  btn btn-secondary btn-lg ">CENE</a> </div>
                    <div class="col-5 col-sm-4"> <a href="materijali.php"
                            class="button-37 col-12  btn btn-secondary btn-lg ">Materijali</a></div>
                </div>
            </article>
        </section>


        <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
                        Zašto odabrati ormare po meri?
                    </h2>

                </header>
                <div class="row">



                    <p class="col-9 paragrafStyle">Ormari za spavaću sobu po meri se savršeno uklapaju, tako da za razliku od gotovih ormara koji se serijski proizvode, mogu potpuno iskoristiti sav prostor planiran za organizaciju i odlaganje.</p>
                 


                    <p class="col-9 paragrafStyle" style="font-size:25px;"> <b> Ono što nas izdvaja od ostalih je mogućnost da u najkraćem roku,
                            za najviše nedelju dana, dobijete preciznu cenu vašeg ormara! </b><br>
                    </p>
                      <p class="col-9 paragrafStyle" >
                       <br>  Naš fokus je na visokom kvalitetu izrade, sa najboljim austrijskim <a class="fw-bolder"
                            href="okovi.php">okovima</a> i komponentama - kako
                        bismo stvorili ormare i garderobere koje su ne samo estetski privlačni, već i funkcionalni dugo dugo
                        godina!<br>
                        Nakon kvalitetne izrade, mi ćemo se pobrinuti o transportu i montaži vašeg novog ormara.<br>
                    </p>

                <p class="col-9 paragrafStyle">
                    Ono što vam garantujemo je: 
                   
                        <br><br>&#9989; <b> &nbsp Cena ormara u najkraćem roku </b>– u roku od sedam dana
                        <br><br>&#9989; <b> &nbsp Najkvalitetnija izrada ormara za sobu</b>– koristimo najkvalitetnije okove i komponente
                        <br><br>&#9989; <b> &nbsp Moderan dizajn i elementi </b>– ormari koji se prilagođavaju vašem prostoru i stilu
                        <br><br>&#9989; <b> &nbsp Kompletna usluga </b>– od ideje do montaže, sve na jednom mestu
                    </p>

                </div>



                <div class="row mt-5">
                    <div class="col-sm-4 col-10 buttonDiv"><button type="button"
                            class="button-37  col-12 btn  btn-warning btn-lg  col-4" role="button"
                            onclick="window.location.href='kontakt.php'">Kontaktirajte nas</button></div>
                </div>

            </article>
        </section>
        
        
               <section class="">

            <article class="container">

                <h3 class="galerija">Pogledajte neke naše radove!</h3>
                <div class="row">


                    <?php
                    $files = scandir('img/ormariGalerija/');
                    foreach ($files as $file) {
                        if ($file !== "." && $file !== "..") {
                            echo "<div class='col-lg-3 col-md-6 mb-4'><div class='bg-image hover-overlay ripple shadow-1-strong rounded'data-ripple-color='light'><img src='img/ormariGalerija/$file' alt='$file' /><a href='javascript:void(0);' data-mdb-toggle='modal' data-mdb-target='#exampleModal1'><div class='mask' style='background-color: rgba(251, 251, 251, 0.2);'></div></a></div></div>";
                        }
                    }
                    ?>



                </div>
                <p class="paragrafStyle galerijaTekst"> Pogledajte više slika u
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

        
        
         <section class="projektovanje color section2   shadow-sm  rounded">
            <article class="container ">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">
          Vrste ormara: Kako izabrati pravi ormar za vašu spavaću sobu?
                    </h2>

                </header>
                <div class="row">

                    <p class="col-9 paragrafStyle">Odabir ormara je važan korak u opremanju doma. 
                    Dobar ormar ne samo da dugo vremena čuva vašu odeću i stvari, već i doprinosi estetici prostora. 
                    U nastavku ćemo vas upoznati sa najčešćim vrstama ormara i dati savete kako da odaberete onaj koji odgovara vašim potrebama.<br><br>
                    </p>
                 
                <h3 class="main_h3 col-10">KLASIČNI ORMARI SA ŠARKAMA</h3>


                      <p class="col-9 paragrafStyle" >
                      Ovo su ormari sa standardnim vratima koja se otvaraju ka spolja. 
                      Dobri su za prostrane sobe, jer zahtevaju dodatni prostor za otvaranje. Njihova prednost je što omogućavaju lak pristup svim delovima unutrašnjosti, ali i njihov klasičan izgled koji se može super uklopiti u neke sobe.

                     <br> <b>Preporuka</b>: Odličan izbor za spavaće sobe u kućama sa više prostora.<br><br>
                    </p>

            <h3 class="main_h3 col-10">KLIZNI ORMARI</h3>


                      <p class="col-9 paragrafStyle" >
                      Ormari sa kliznim vratima su savršeno rešenje za manje prostore, jer im ne treba dodatni prostor pri otvaranju. 
                      Osim funkcionalnosti, pružaju vrlo moderan izgled i često dolaze sa ugrađenim ogledalima koja dodatno vizuelno proširuju prostor.



                     <br> <b>Preporuka</b>: Idealan za manje stanove ili modernije enterijere.<br><br>
                    </p>
                    

                <h3 class="main_h3 col-10">UGRADNI ORMARI</h3>


                      <p class="col-9 paragrafStyle" >
                     Ugradni ormari po meri koriste svaki centimetar prostora, od poda do plafona. 
                     Oni se prilagođavaju vašim potrebama i omogućavaju potpuno personalizovan raspored polica, fioka i vešalica.



                     <br> <b>Preporuka</b>: Najbolje rešenje za spavaće sobe gde je svaki deo prostora važan.<br><br>
                    
                    </p>

                <h3 class="main_h3 col-10">GARDEROBERI</h3>


                      <p class="col-9 paragrafStyle" >
                     Garderoberi su prostrani ormari, često sa zasebnim odeljcima za odeću, obuću i dodatke. 
                     Mogu biti otvoreni ili zatvoreni, u zavisnosti od vaših želja, dizajna sobe, i naravno prostora.



                     <br> <b>Preporuka</b>: Luksuzna opcija za veće domove sa dodatnim prostorom za odlaganje.
                    
                    </p>

    

                </div>

            </article>
        </section>
        
        
        <section class="custom-section">
    <div class="image-container">
      <div class="image-box">
        <img src="img/ormariGalerija/ormari-po-meri-beograd.webp" alt="AMERICKI PLAKARI PO MERI Beograd i Pancevo">
        <a href="https://akcent.rs/americki-plakari.php" class="button-37  col-12 btn  btn-warning btn-lg  col-4 btnn" >Američki plakari</a>
      </div>
      <div class="image-box">
        <img src="img/plakariGalerija/plakari-po-meri-spavaca-soba-1.webp" alt="Plakari za sobe">
        <a href="https://akcent.rs/plakari-po-meri.php" class="button-37  col-12 btn  btn-warning btn-lg  col-4 btnn" >Svi plakari</a>
      </div>
      <div class="image-box">
        <img src="img/3dprojektovanjeGalerija/kuhinja-po-meri-projektovanje.webp" alt="3D projektovanje">
        <a href="https://akcent.rs/3d-projektovanje-kuhinja-i-plakara.php" class="button-37  col-12 btn  btn-warning btn-lg  col-4 btnn" >3D projektovanje</a>
      </div>
    </div>
    </section>
        
        

        

      
        <?php include("komponente/zastoMi.php"); ?>
        
     
        <?php include("komponente/kakoDoNas.php"); ?>


        <section class="okovi">
            <article class="container ">
                <header class="header mb-5">
                    <h2 class="main_h3 col-10">Šta koristimo prilikom izrade ormara po meri?</h2>

                </header>
                <div class="row">



                    <p class="col-9 paragrafStyle">Saznajte šta koristimo ormare za sobu,
                        i šta koristimo pri montaži. Čak i najmanja sitnica, poput šrafa, tipli, žičanih i drugih
                        elemenata, utiče na razliku u kvalitetu plakara.
                        Ovi, naizgled, detalji, često doprinose dugotrajnosti plakara i generalno funkcionalnosti koja
                        traje. </p>


                </div>
            </article>



            <section class="">
                <article class="container ">
                    <header class="row header mb-5">
                        <h3 class="main_h3 col-10">SPAX šfrafovi</h3>


                    </header>
                    <div class="">

                        <div class="imgSpax"></div>

                        <p class="mainParagraf">SPAX šrafovi su poznati po svom visokom kvalitetom, što ih čini
                            popularnim izborom za plakare po meri. Veoma se često koriste zbog izdržljivosti i
                            pouzdanosti. <br> Evo nekoliko karakteristika
                            SPAX srafova:</p>

                        <ol>
                            <li>
                                <p><b> Visokokvalitetni materijali:</b>: SPAX srafovi su izrađeni od visokokvalitetnog
                                    čelika,
                                    što im omogućava da izdrže velike napore i pruže pouzdanu vezu.</p>
                            </li>
                            <li>
                                <p><b>Funkcionalan dizajn, za laku montažu:</b> Zahvaljujući specijalno dizajniranim
                                    navojima, SPAX
                                    srafovi
                                    se lako uvlače u materijal bez potrebe za predbušenjem, što olakšava i
                                    ubrzava
                                    proces montaže.</p>
                            </li>
                            <li>
                                <p> <b>Višestruka i raznovrsna upotreba:</b> Ovi srafovi se koriste za različite
                                    potrebe,
                                    uključujući
                                    plakare, kuhinje, i druge komade od drveta.</p>
                            </li>
                            <li>
                                <p><b> Različite vrste premaza:</b> SPAX srafovi dolaze sa različitim vrstama premaza,
                                    uključujući cink, pocinkovanje, crni oksid i više, pružajući dodatnu zaštitu
                                    od
                                    korozije.
                                </p>
                            </li>
                            <li>
                                <p> <b>Nemački kvalitet:</b> Proizvedeni u Nemačkoj, SPAX srafovi su podvrgnuti strogoj
                                    kontroli kvaliteta, obezbeđujući dugotrajnost i pouzdanost svakog komada.
                                </p>
                            </li>
                        </ol>
                        <p><b>Ukratko, SPAX srafovi su sinonim za vrhunski kvalitet, pouzdanost i dugotrajnost,
                                što ih čini idealnim izborom za ugradnju plakara, kuhinja i drugih projekata.</b></p>











                    </div>


                </article>
            </section>




            <section class="">
                <article class="container ">
                    <header class="row header mb-5">
                        <h3 class="main_h3 col-10">FISHER tiple</h3>

                    </header>
                    <div class="">

                        <div class="imgfisher"></div>
                        <p class="mainParagraf">Fischer DuoPower je popularan tip univerzalnih tipli koji se koristi za
                            pričvršćivanje različitih predmeta na zidove od različitih materijala kao što su
                            gips, beton, cigla, drvo i drugi materijali. Evo nekoliko ključnih karakteristika
                            ovih tipli i zašto su odlične za montažu kuhinja:</p>


                        <ol>
                            <li>
                                <p><b>Dvostruka funkcija:</b> Kao što i samo ime sugeriše, DuoPower kombinuje dve
                                    funkcije u jednoj tipli. Unutar tiple postoje dve različite zone koje
                                    omogućavaju optimalno širenje u različitim materijalima.</p>
                            </li>
                            <li>
                                <p><b>Prilagodljivost:</b> Zahvaljujući svojoj dvostrukoj funkciji, one su
                                    prilagodljive različitim materijalima i omogućavaju sigurno pričvršćivanje
                                    čak i u mekanijim ili krhkijim površinama poput gipsa.</p>
                            </li>
                            <li>
                                <p><b>Jednostavna upotreba:</b> Fischer DuoPower tiple su jednostavni za upotrebu. Kada
                                    se uvlače u zid, prvo se koristi prvobitna zona za širenje, a zatim dodatna
                                    zona za dodatnu stabilnost.</p>
                            </li>
                            <li>
                                <p><b>Široka primena:</b> Fisher tiple se mogu koristiti za montažu kuhinja, polica,
                                    visećih
                                    elemenata, ogledala, svetiljki i raznih drugih predmeta u domaćinstvu ili na
                                    radnom mestu.
                                </p>
                            </li>
                            <li>
                                <p> <b> Visok kvalitet:</b> Fischer kompanija je poznata po proizvodnji kvalitetnih
                                    proizvoda, pa
                                    i DuoPower tiple garantuju visok kvalitet i dugotrajnost.
                                </p>
                            </li>
                        </ol>
                        <p><b>Ukratko, Fischer DuoPower tipli su pouzdan izbor za sigurno pričvršćivanje
                                različitih predmeta na zidove, pružajući jednostavnu upotrebu i visok
                                kvalitet. Mi ih zbog svih ovih prednosti koristimo pri ugradnji plakara po meri, kako
                                bismo obezbedilli maksimalnu stabilnost.</b></p>



                    </div>


                </article>
            </section>




            <section class="  shadow-sm  ">
                <article class="container ">
                    <header class="row header mb-5">
                        <h3 class="main_h3 col-10 ">INOXA žičani elementi</h3>

                    </header>
                    <div class="">




                        <div class="imgInoxa"></div>

                        <p class="mainParagraf">Firma Inoxa nudi širok spektar žičanih elemenata za kuhinje, plakare i
                            druge
                            primene. U nastavku pročitajte
                            neke od njihovih karakteristika:</p>


                        <ol>
                            <li>
                                <p><b>Visokokvalitetni materijali:</b> Žičani elementi firme Inoxa izrađeni su od
                                    visokokvalitetnog nerđajućeg čelika, što im obezbeđuje izdržljivost i
                                    dugotrajnost.</p>
                            </li>
                            <li>
                                <p><b>Raznovrsnost proizvoda:</b> Inoxa nudi širok spektar elemenata,
                                    uključujući police, korpe, nosače, organizatore i druge dodatke za
                                    organizaciju kuhinja, plakara, itd..</p>
                            </li>

                            <li>
                                <p> <b>Estetski privlačni:</b> Poredfunkcionalnosti, žičani elementi firme Inoxa imaju
                                    i estetski privlačan dizajn koji doprinosi celokupnom izgledu kuhinje. Čist i
                                    moderan
                                    izgled žičanih elemenata može poboljšati estetiku prostora i naravno doprineti
                                    boljoj organizaciji prostora.
                                </p>
                            </li>
                            <li>
                                <p> <b>Prilagodljivost:</b> Inoxa pruža mogućnost prilagođavanja žičanih elemenata
                                    prema potrebama klijenata. Klijenti mogu da biraju između različitih dimenzija,
                                    oblika i konfiguracija kako bi se elementi uklopili u plakar ili kuhinju.
                                </p>
                            </li>
                        </ol>

                        <p><b>Ukratko, zicani elementi firme Inoxa kombinuju visokokvalitetne materijale,
                                funkcionalni dizajn i estetsku privlačnost kako bi pružili efikasna rešenja za
                                organizaciju kuhinjskih prostora.</b></p>
                    </div>



                </article>
            </section>

            </article>
        </section>



        <section class="porucivanje   shadow-sm ">
            <article class="container">
                <header class="row header mb-5">
                    <h2 class="main_h3 col-10">Najčešća pitanja </h2>

                </header>
                <div class=" row">
                    <?php include("komponente/pitanjaPlakari.php"); ?>
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
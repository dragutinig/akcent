<!doctype html>
<html class="no-js" lang="sr">

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
    <title>Galerija kuhinja i plakara po meri visokog kvaliteta</title>
    <meta name="description" content="Pogledajte galeriju naših projekata. Kuhinje i plakari po meri kvalitetne izrade, sa najboljim okovima. Bez skrivenih troskova.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="favicon.ico">
    
    <link rel="canonical" href="https://akcent.rs/namestaj-po-meri-galerija.php" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/maingal.css">

    <meta name="theme-color" content="#fafafa">

    <?php include("komponente/seo.php"); ?>

</head>

<body>
    <main>
        <?php include("komponente/nav.php"); ?>
        <div style="height:50px;"></div>
        <div style="height:50px;"></div>

        <header>
            <div>
                <h1>Galerija: Akcent nameštaj po meri</h1>
                <p>Naše kuhinje po meri, plakari po meri, i ostali nameštaj se radi isključivo po narudžbini. 
                 Unapred planiramo cene i poštujemo rokove! <br> 
                Pogledajte
                    naše projekte, i pronađite inspiraciju za vaš enterijer.</p>
            </div>
        </header>

        <section class="container">
            <section class="gallery-grid">
                <?php
                $files = scandir('gallery/');
                $images = array(); // Niz za sve slike u galeriji
                foreach ($files as $file) {
                    if ($file !== "." && $file !== "..") {
                        $images[] = $file; // Dodajemo sliku u niz za kasniji pristup
                        echo "<div class='gallery-item'>
                                <div class='bg-image hover-overlay ripple shadow-1-strong rounded'>
                                    <img src='gallery/$file' alt='$file' loading='lazy' class='gallery-img' data-bs-toggle='modal' data-bs-target='#imageModal' data-image='gallery/$file'/>
                                </div>
                              </div>";
                    }
                }
                ?>
            </section>
        </section>

        <!-- Modal za prikaz slika -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid" alt="Galerija slika" />
                    </div>
                    <div class="modal-footer" style="justify-content: center">
                        <button type="button" class="btn btn-secondary" id="prevImage">Prethodna</button>
                        <button type="button" class="btn btn-secondary" id="nextImage">Sledeća</button>
                    </div>
                </div>
            </div>
        </div>
<?php include("komponente/cookie-banner.php"); ?>
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

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const modalImage = document.getElementById('modalImage');
    const prevButton = document.getElementById('prevImage');
    const nextButton = document.getElementById('nextImage');

    let currentImageIndex = 0;
    const images = <?php echo json_encode($images); ?>;  // PHP niz slika

    // Funkcija za promeniti sliku
    function changeImage(index) {
        modalImage.src = 'gallery/' + images[index];
        currentImageIndex = index;
    }

    const galleryImages = document.querySelectorAll('.gallery-img');
    galleryImages.forEach((img, index) => {
        img.addEventListener('click', function () {
            changeImage(index);
        });
    });

    prevButton.addEventListener('click', function () {
        if (currentImageIndex > 0) {
            changeImage(currentImageIndex - 1);
        }
    });

    nextButton.addEventListener('click', function () {
        if (currentImageIndex < images.length - 1) {
            changeImage(currentImageIndex + 1);
        }
    });

    // Swipe funkcionalnost (touch events)
    let startX = 0;
    let endX = 0;

    // Slušalac za početak dodira ekrana
    modalImage.addEventListener('touchstart', function (e) {
        startX = e.touches[0].clientX;  // Snimamo početnu X poziciju prsta
    });

    // Slušalac za završetak dodira ekrana
    modalImage.addEventListener('touchend', function (e) {
        endX = e.changedTouches[0].clientX;  // Snimamo završnu X poziciju prsta

        // Detektujemo pravac skrolovanja
        if (startX > endX + 50) {  // Ako je prst pomeren ulijevo (swipe desno)
            if (currentImageIndex < images.length - 1) {
                changeImage(currentImageIndex + 1);
            }
        } else if (startX < endX - 50) {  // Ako je prst pomeren udesno (swipe levo)
            if (currentImageIndex > 0) {
                changeImage(currentImageIndex - 1);
            }
        }
    });

    // Navigacija putem strelica na tastaturi
    document.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            event.preventDefault();
        }

        if (event.key === 'ArrowLeft') {  // Leva strelica
            if (currentImageIndex > 0) {
                changeImage(currentImageIndex - 1);
            }
        }
        else if (event.key === 'ArrowRight') {  // Desna strelica
            if (currentImageIndex < images.length - 1) {
                changeImage(currentImageIndex + 1);
            }
        }
    });

    // Resetovanje indeksa kada se modal zatvori
    document.getElementById('imageModal').addEventListener('hidden.bs.modal', function () {
        currentImageIndex = 0;
    });
});

    </script>

    <script>
        window.ga = function () { ga.q.push(arguments) };
        ga.q = [];
        ga.l = +new Date;
        ga('create', 'UA-XXXXX-Y', 'auto');
        ga('set', 'transport', 'beacon');
        ga('send', 'pageview');
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>

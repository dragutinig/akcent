<!doctype html>
<html class="no-js" lang="sr">

<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HDLXHWERJK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16983581852');
  gtag('config', 'G-HDLXHWERJK'); // Google Analytics 4

</script>

<!-- Event snippet for Contact conversion page
In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
<script>
function gtag_report_conversion(url) {
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  
    // Google Ads konverzija
  gtag('event', 'conversion', {
      'send_to': 'AW-16983581852/4pCgCI6f1LUaEJzJs6I_',
     'event_callback': function() {
    console.log("Event poslat Google Ads");
  });
  
  // Google Analytics 4 - event
  gtag('event', 'klik_na_kontakt_dugme', {
    'event_category': 'kontakt',
    'event_label': 'dugme za kontakt',
    'value': 1
  });
  
  return false;
}
</script>




    <meta charset="utf-8">
    <title>Akcent namestaj po meri: Kontakt</title>
    <meta name="description" content="Kontaktirajte nas za kuhinje i plakare po meri. Pronađite sve informacije o našim uslugama i načinima komunikacije.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    
    <link rel="canonical" href="https://akcent.rs/kontakt.php" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/kontakt.css">
    <link rel="stylesheet" href="css/nav.css">
     <link rel="stylesheet" href="css/index.css">
     <link rel="stylesheet" href="css/lead-form.css">
    <meta name="theme-color" content="#fafafa">
</head>

<body>
    <main>
        <?php include("komponente/nav.php"); ?>

        <!-- Header -->
        <header class="container text-center py-5 bg-light shadow-sm rounded">
            <h1 class="display-5 fw-bold">Kontakt</h1>
            <p class="lead">Istražite opcije kako najlakše da nas kontaktirate!</p>
        </header>

        <!-- Kontakt sekcija -->
     <section style="padding: 100px;">
    <div class="contact-container">
          <a style="color:inherit;" onclick="gtag_report_conversion(this.href);" href="tel:+381616485508">
        <div class="contact-card phone">
            <div class="icon phone-icon"></div>
            <h3>Telefon</h3>
            <p>Ponekad zbog obima posla ne možemo odmah da se javimo. Kontaktirajte nas za brze informacije.</p>
            <p class="text-primary h4">+381 61 648 55 08</p>
        </div></a>

      
      <a style="color:inherit;" onclick="gtag_report_conversion('mailto:akcentnamestaj@gmail.com');" href="mailto:akcentnamestaj@gmail.com">
      <div class="contact-card email">
    <div class="icon email-icon"></div>
    <h3>E-mail</h3>
    <p class="kontaktSmallText">E-mail je uvek dobra opcija. Preporučujemo da prvi kontakt bude preko jedne od mreža ili e-mail.</p>
    <p class="text-primary h4 email-address">akcentnamestaj@gmail.com</p>
</div></a>

     <a style="color:inherit;" onclick="gtag_report_conversion('viber://chat?number=%2B381616485508');" href="viber://chat?number=%2B381616485508">
        <div class="contact-card viber">
            <div class="icon viber-icon"></div>
            <h3>Viber</h3>
            <p>Najviše komuniciramo putem Vibera. Pošaljite nam ideje, mere i pitanja.</p>
            <p class="text-primary h4">+381 61 648 55 08</p>
        </div></a>
        
       <a style="color:inherit;" onclick="gtag_report_conversion('https://wa.me/381616485508');" href="https://wa.me/381616485508">
        <div class="contact-card whatsapp">
            <div class="icon whatsapp-icon"></div>
            <h3>WhatsApp</h3>
            <p>Komunicirajte sa nama putem WhatsApp-a. Brzo i jednostavno!</p>
            <p class="text-primary h4">+381 61 648 55 08</p>
        </div></a>
    </div>
</section>

<?php include("komponente/lead-form.php"); ?>
        <?php include("komponente/footer.php"); ?>
        <?php include("komponente/cookie-banner.php"); ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/vendor/modernizr-3.8.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-3.6.0.min.js"><\/script>')
    </script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
<!--
    <script>
        window.ga = function () {
            ga.q.push(arguments)
        };
        ga.q = [];
        ga.l = +new Date;
        ga('create', 'AW-1698358185', 'auto');
        ga('set', 'transport', 'beacon');
        ga('send', 'pageview')
    </script>
    <script src="https://www.google-analytics.com/analytics.js" async></script>
    -->
</body>

</html>

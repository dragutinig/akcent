<?php
  $current_page = basename($_SERVER['PHP_SELF']);
  if ($current_page == 'kontakt.php' || $current_page == 'index.php') {
?>
<script>
  function gtag_report_conversion(url) {
    var callback = function () {
      if (typeof(url) != 'undefined') {
        window.location = url;
      }
    };
    gtag('event', 'conversion', {
        'send_to': 'AW-16983581852/4pCgCI6f1LUaEJzJs6I_',
        'event_callback': callback
    });
    return false;
  }
</script>
<?php } ?>

<footer class="akcent-site-footer" aria-label="Futer sajta">
  <div class="container akcent-footer-main">
    <div class="akcent-footer-grid">
      <section class="akcent-footer-card akcent-footer-brand" aria-labelledby="footer-brand-title">
        <h2 id="footer-brand-title">Akcent Nameštaj</h2>
        <p>Izrada nameštaja po meri za stanove, kuće i poslovne prostore u Pančevu i Beogradu.</p>
        <a class="akcent-footer-cta" href="kontakt.php">Pošaljite upit</a>
      </section>

      <section class="akcent-footer-card" aria-labelledby="footer-contact-title">
        <h2 id="footer-contact-title">Kontakt</h2>
        <ul class="akcent-footer-list">
          <li><strong>Adresa:</strong> Pančevo, Srbija</li>
          <li>
            <strong>Email:</strong>
            <a href="mailto:akcentnamestaj@gmail.com" onclick="gtag_report_conversion(); return false;">akcentnamestaj@gmail.com</a>
          </li>
          <li>
            <strong>Telefon:</strong>
            <a href="tel:+381616485508" onclick="gtag_report_conversion(); return false;">+381 61 648 5508</a>
          </li>
          <li><strong>Radno vreme:</strong> Pon–Pet 09:00–17:00, Sub 09:00–15:00</li>
        </ul>
      </section>

      <section class="akcent-footer-card" aria-labelledby="footer-links-title">
        <h2 id="footer-links-title">Ponuda</h2>
        <ul class="akcent-footer-links">
          <li><a href="kuhinje-po-meri.php">Kuhinje po meri</a></li>
          <li><a href="plakari-po-meri.php">Plakari po meri</a></li>
          <li><a href="namestaj-za-dnevnu-sobu-po-meri.php">Dnevna soba</a></li>
          <li><a href="trpezarijski-stolovi.php">Trpezarijski stolovi</a></li>
          <li><a href="klub-stolovi.php">Klub stolovi</a></li>
          <li><a href="kupatilski-elementi.php">Kupatilski elementi</a></li>
        </ul>
      </section>

      <section class="akcent-footer-card" aria-labelledby="footer-info-title">
        <h2 id="footer-info-title">Korisni linkovi</h2>
        <ul class="akcent-footer-links">
          <li><a href="cene.php">Cene kuhinja po meri</a></li>
          <li><a href="onama.php">O nama</a></li>
          <li><a href="3d-projektovanje-kuhinja-i-plakara.php">3D projektovanje</a></li>
          <li><a href="/blog/">Blog</a></li>
          <li><a href="/blog/uredjenje-prostora">Uređenje prostora</a></li>
          <li><a href="/blog/stilovi-u-enterijeru">Stilovi u enterijeru</a></li>
        </ul>
      </section>
    </div>
  </div>

  <div class="akcent-footer-bottom">
    <div class="container akcent-footer-bottom-inner">
      <div class="social-links" aria-label="Društvene mreže">
        <a href="https://www.facebook.com/akcentnamestaj" target="_blank" rel="noopener noreferrer" aria-label="Facebook">Facebook</a>
        <a href="https://www.instagram.com/akcentnamestaj/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">Instagram</a>
        <a href="https://www.pinterest.com/akcentnamestaj/" target="_blank" rel="noopener noreferrer" aria-label="Pinterest">Pinterest</a>
      </div>
      <p>© 2026 Akcent Nameštaj. Sva prava zadržana.</p>
    </div>
  </div>
</footer>

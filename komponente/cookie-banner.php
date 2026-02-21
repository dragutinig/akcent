<?php
// Prikazi banner dok korisnik ne izabere opciju za kolačiće
$cookieChoice = $_COOKIE['cookie_accepted'] ?? null;
$showBanner = !in_array($cookieChoice, ['true', 'necessary'], true);
?>

<?php if ($showBanner): ?>
<div id="cookie-banner" class="cookie-banner" role="dialog" aria-live="polite" aria-label="Obaveštenje o kolačićima" aria-modal="false">
  <div class="cookie-banner-content">
    <p class="cookie-banner-title">Poštujemo vašu privatnost</p>
    <p class="cookie-banner-text">
      Koristimo neophodne kolačiće za rad sajta i opcione kolačiće za analitiku kako bismo unapredili korisničko iskustvo.
    </p>
    <div class="cookie-banner-actions">
      <button id="accept-cookies" class="cookie-btn cookie-btn-primary" type="button">Prihvati sve</button>
      <button id="reject-cookies" class="cookie-btn cookie-btn-secondary" type="button">Samo neophodni</button>
    </div>
  </div>
</div>

<script>
(function () {
  var banner = document.getElementById('cookie-banner');
  var acceptBtn = document.getElementById('accept-cookies');
  var rejectBtn = document.getElementById('reject-cookies');
  if (!banner) return;

  function setCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + value + '; expires=' + date.toUTCString() + '; path=/; SameSite=Lax';
  }

  function closeBanner() {
    banner.style.display = 'none';
  }

  if (acceptBtn) {
    acceptBtn.addEventListener('click', function () {
      setCookie('cookie_accepted', 'true', 365);
      closeBanner();
    });
  }

  if (rejectBtn) {
    rejectBtn.addEventListener('click', function () {
      setCookie('cookie_accepted', 'necessary', 365);
      closeBanner();
    });
  }
})();
</script>
<?php endif; ?>

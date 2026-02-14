<?php
// Provera da li je korisnik već prihvatio kolačiće
if (!isset($_COOKIE['cookie_accepted'])) {
    $showBanner = true;
} else {
    $showBanner = false;
}
?>

<?php if ($showBanner): ?>
    <div id="cookie-banner" class="cookie-banner" style="position: fixed; bottom: 0; left: 0; right: 0; background: #222; color: #fff; padding: 15px; text-align: center; z-index: 9999;">
        <p style="margin: 0; font-size: 14px;">
            Naš sajt koristi kolačiće kako bi poboljšao korisničko iskustvo. Klikom na <strong>'Prihvatam'</strong> slažete se sa upotrebom kolačića.
        </p>
        <button id="accept-cookies" style="margin-top: 10px; padding: 8px 15px; background: #f1d600; border: none; border-radius: 5px; cursor: pointer;">
            Prihvatam
        </button>
    </div>

    <script>
        document.getElementById("accept-cookies").addEventListener("click", function() {
            // Postavljamo kolačić na 1 godinu
            var date = new Date();
            date.setFullYear(date.getFullYear() + 1);
            document.cookie = "cookie_accepted=true; expires=" + date.toUTCString() + "; path=/";

            // Sakrivamo baner
            document.getElementById("cookie-banner").style.display = "none";
        });
    </script>
<?php endif; ?>

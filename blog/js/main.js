document.addEventListener('DOMContentLoaded', function () {
    const acceptCookiesButton = document.getElementById('accept-cookies');
    const cookieBanner = document.getElementById('cookie-banner');

    if (acceptCookiesButton && cookieBanner) {
        acceptCookiesButton.addEventListener('click', function () {
            document.cookie = 'cookie_accepted=true; path=/; max-age=' + 60 * 60 * 24 * 365;
            cookieBanner.style.display = 'none';
        });
    }

    const toggle = document.querySelector('.dropdown-toggle');
    const menu = document.querySelector('.dropdown-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpen = menu.classList.contains('open');
            menu.classList.toggle('open', !isOpen);
            toggle.setAttribute('aria-expanded', (!isOpen).toString());
        });

        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
});

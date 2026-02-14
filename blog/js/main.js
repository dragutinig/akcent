document.addEventListener("DOMContentLoaded", function() {
    // Kada korisnik klikne na "Prihvatam"
    document.getElementById("accept-cookies").addEventListener("click", function() {
        // Postavi kolačić koji označava da je korisnik prihvatio kolačiće
        document.cookie = "cookie_accepted=true; path=/; max-age=" + 60 * 60 * 24 * 365; // Kolačić važi 1 godinu
        // Sakrij banner
        document.getElementById("cookie-banner").style.display = "none";
    });
});



   // JavaScript za otvaranje/zatvaranje dropdown menija
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('.dropdown-toggle');
        const menu = document.querySelector('.dropdown-menu');

        toggle.addEventListener('click', function (e) {
            e.preventDefault(); // Sprečava skakanje stranice
            const isOpen = menu.style.display === 'block';
            menu.style.display = isOpen ? 'none' : 'block';
        });

        // Zatvori meni kada se klikne van njega
        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.style.display = 'none';
            }
        });
    });
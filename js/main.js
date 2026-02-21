
$(document).ready(function(){
    const $menuButton = $('.navbar-fostrap');
    const $nav = $('.nav-fostrap');
    const mobileBreakpoint = 1199;

    function closeMobileNav() {
        $nav.removeClass('visible');
        $('body').removeClass('cover-bg');
        $menuButton.attr('aria-expanded', 'false');
        $('.has-dropdown').removeClass('submenu-open');
        $('.submenu-toggle').attr('aria-expanded', 'false');
    }

    $menuButton.on('click', function(){
        const willOpen = !$nav.hasClass('visible');
        $nav.toggleClass('visible', willOpen);
        $('body').toggleClass('cover-bg', willOpen);
        $menuButton.attr('aria-expanded', willOpen ? 'true' : 'false');
    });

    $('.submenu-toggle').on('click', function (event) {
        if (window.innerWidth > mobileBreakpoint) {
            return;
        }

        event.preventDefault();
        const $parent = $(this).closest('.has-dropdown');
        const isOpen = $parent.hasClass('submenu-open');

        $parent.toggleClass('submenu-open', !isOpen);
        $(this).attr('aria-expanded', isOpen ? 'false' : 'true');
    });

    $('.nav-fostrap a').on('click', function () {
        if (window.innerWidth <= mobileBreakpoint) {
            closeMobileNav();
        }
    });

    $(window).on('resize', function () {
        if (window.innerWidth > mobileBreakpoint) {
            closeMobileNav();
        }
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMobileNav();
        }
    });

});





// Get the button:
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}




document.addEventListener("DOMContentLoaded", function() {
    // Kada korisnik klikne na "Prihvatam"
    document.getElementById("accept-cookies").addEventListener("click", function() {
        // Postavi kolačić koji označava da je korisnik prihvatio kolačiće
        document.cookie = "cookie_accepted=true; path=/; max-age=" + 60 * 60 * 24 * 365; // Kolačić važi 1 godinu
        // Sakrij banner
        document.getElementById("cookie-banner").style.display = "none";
    });
});

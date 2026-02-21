
$(document).ready(function(){
    const $menuButton = $('.navbar-fostrap');
    const $nav = $('.nav-fostrap');
    const mobileBreakpoint = 1199;

    function closeMobileNav() {
        $nav.removeClass('visible');
        $('body').removeClass('cover-bg menu-open');
        $menuButton.attr('aria-expanded', 'false');
        $('.has-dropdown').removeClass('submenu-open');
        $('.submenu-toggle').attr('aria-expanded', 'false');
    }

    $menuButton.on('click', function(){
        const willOpen = !$nav.hasClass('visible');
        $nav.toggleClass('visible', willOpen);
        $('body').toggleClass('cover-bg', willOpen);
        $('body').toggleClass('menu-open', willOpen);
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
  if (!mybutton) return;
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
    const acceptButton = document.getElementById("accept-cookies");
    const rejectButton = document.getElementById("reject-cookies");
    const cookieBanner = document.getElementById("cookie-banner");

    function setCookiePreference(value) {
      document.cookie = "cookie_accepted=" + value + "; path=/; max-age=" + 60 * 60 * 24 * 365 + "; SameSite=Lax";
    }

    function applyConsentMode(value) {
      if (typeof window.gtag !== 'function') return;
      const granted = value === 'true';
      window.gtag('consent', 'update', {
        analytics_storage: granted ? 'granted' : 'denied',
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied'
      });
    }

    if (acceptButton && cookieBanner) {
      acceptButton.addEventListener("click", function() {
        setCookiePreference('true');
        applyConsentMode('true');
        cookieBanner.style.display = "none";
      });
    }

    if (rejectButton && cookieBanner) {
      rejectButton.addEventListener("click", function() {
        setCookiePreference('necessary');
        applyConsentMode('necessary');
        cookieBanner.style.display = "none";
      });
    }


    // Hero enhancement: doda badge iznad naslova i benefit checkmarks ispod CTA gde nedostaju
    const heroSection = document.querySelector('.section1');
    if (heroSection) {
      const heroContainer = heroSection.querySelector('.hero-content, .vertical, .container');
      const heroTitle = heroSection.querySelector('.main_h1, h1');

      if (heroContainer && heroTitle && !heroSection.querySelector('.hero-eyebrow')) {
        const eyebrow = document.createElement('p');
        eyebrow.className = 'hero-eyebrow auto-hero-eyebrow';
        eyebrow.textContent = 'NAMEŠTAJ PO MERI • BEOGRAD I PANČEVO';
        heroTitle.parentNode.insertBefore(eyebrow, heroTitle);
      }

      if (heroContainer && !heroSection.querySelector('.hero-points')) {
        const points = document.createElement('ul');
        points.className = 'hero-points auto-hero-points';
        points.innerHTML = '<li>✓ Besplatna procena</li><li>✓ 3D projektovanje</li><li>✓ Precizna montaža</li>';

        const cta = heroSection.querySelector('.hero-cta, .brzoBiranje');
        if (cta) {
          cta.insertAdjacentElement('afterend', points);
        } else {
          heroContainer.appendChild(points);
        }
      }
    }
    // Core Web Vitals/UX: lazy-load slike ispod pregiba bez menjanja hero slika
    const allImages = Array.from(document.querySelectorAll('img'));
    allImages.forEach((img, index) => {
      if (index > 1 && !img.hasAttribute('loading')) {
        img.setAttribute('loading', 'lazy');
      }
      if (!img.hasAttribute('decoding')) {
        img.setAttribute('decoding', 'async');
      }
    });
});

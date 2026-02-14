
$(document).ready(function(){
    $('.navbar-fostrap').click(function(){
        $('.nav-fostrap').toggleClass('visible');
        $('body').toggleClass('cover-bg');
    });
    
    
    window.onhashchange = () => console.log("Hash changed to:", window.location.hash);

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
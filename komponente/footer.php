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



<footer class="akcent-site-footer text-white text-center text-lg-start">

    <div class="container p-4">

        <div class="row mt-4">

            <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">Kontakt</h5>

               

                <p>
                    Poziv, SMS, Viber, WhatsApp, E-mail
                </p>


                <button type="button" class="button-37  col-12 btn  btn btn-success btn-lg  col-4" role="button"
                    onclick="window.location.href='https://akcent.rs/kontakt.php'">Kontakt</button>

            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">

                <h5 class="text-uppercase mb-4">Adresa</h5>
                <ul class="fa-ul" style="padding:0; list-style:none; ">
                    <li class="mb-3">
                        <span class="fa-li"><i class="fas fa-home"></i></span><span class="ms-2">26000 Pančevo, RS</span>
                    </li>
                    <li class="mb-3">
                       <a style="color:inherit;" href="mailto:akcentnamestaj@gmail.com" onclick="gtag_report_conversion(); return false;">  <span class="fa-li"><i class="fas fa-envelope"></i></span><span
                            class="ms-2">akcentnamestaj@gmail.com</span></a>
                    </li>
                    <li class="mb-3">
                         <a style="color:inherit;" href="tel:+381616485508" onclick="gtag_report_conversion(); return false;"> <span class="fa-li"><i class="fas fa-phone"></i></span><span class="ms-2">+381616488508</span></a>
                    </li>

                </ul>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">Radno vreme</h5>

                <table class="table text-center text-white">
                    <tbody class="font-weight-normal">
                        <tr>
                            <td>pon - pet:</td>
                            <td>09:00 - 17:00</td>
                        </tr>
                        <tr>
                            <td>sub:</td>
                            <td>09:00 - 15:00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        

    </div>
        <div class="container p-4">

        <div class="row mt-4">

            <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">PONUDA</h5>

               

                <ul class="fa-ul" style="padding:0; list-style:none;display: grid;grid-template-columns: repeat(2, 1fr);gap: 10px;">
               <li><a style="color:#fff !important" href="kuhinje-po-meri.php">Kuhinje po meri</a></li>
               <li><a style="color:#fff !important" href="#">Trpezarijski stolovi</a></li>
               <li><a style="color:#fff !important" href="namestaj-za-dnevnu-sobu-po-meri.php">Dnevna soba
               <li><a style="color:#fff !important" href="#">Klub stolovi</a></li>
               <li><a style="color:#fff !important" href="#">TV komode</a></li>
               <li><a style="color:#fff !important" href="#">Pregradne police</a></li>
               <li><a style="color:#fff !important" href="#">Police za knjige</a></li>
               <li><a style="color:#fff !important" href="plakari-po-meri.php">Plakari</a></li>
               <li><a style="color:#fff !important" href="ormari-za-sobu.php">Ormari za sobu</a></li>
               <li><a style="color:#fff !important" href="garderoberi.php">Garderoberi</a></li>
               <li><a style="color:#fff !important" href="americki-plakari.php">Američki plakari</a></li>
               <li><a style="color:#fff !important" href="kupatilski-elementi.php">Kupatilski elementi</a></li>
               
               </ul>

            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">

                <h5 class="text-uppercase mb-4">POSLOVANJE</h5>
                 <ul class="fa-ul" style="padding:0; list-style:none;color:#fff !important;display: grid;gap: 10px">
               <li><a style="color:#fff !important" href="cene.php">CENA KUHINJA PO MERI</a></li>
               <li><a style="color:#fff !important" href="onama.php">O nama</a></li>
               <li><a style="color:#fff !important" href="3d-projektovanje-kuhinja-i-plakara.php">3D projektovanje</a></li>
           
               </ul>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">BLOG</h5>

                   <ul class="fa-ul" style="padding:0; list-style:none; color:#fff !important;display: grid;gap:10px">
               <li><a style="color:#fff !important" href="/blog/uredjenje-prostora">Uredjenje prostora</a></li>
               <li><a style="color:#fff !important" href="/blog/stilovi-u-enterijeru">Stilovi u enterijeru</a></li>
               <li><a style="color:#fff !important" href="/blog/kupovina-namestaja">Kupovina nameštaja</a></li>
               <li><a style="color:#fff !important" href="/blog/restauracija-namestaja">Restauracija</a></li>
           
               </ul>
               
        
        </div>
        

    </div>

    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        
        <div class=social-links>
        <!-- Facebook -->
               <a href="https://www.facebook.com/share/1Eb1DDx6CT/?mibextid=wwXIfr" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" fill="white">
                        <path d="M449.446,0c34.525,0 62.554,28.03 62.554,62.554l0,386.892c0,34.524 -28.03,62.554 -62.554,62.554l-106.468,0l0,-192.915l66.6,0l12.672,-82.621l-79.272,0l0,-53.617c0,-22.603 11.073,-44.636 46.58,-44.636l36.042,0l0,-70.34c0,0 -32.71,-5.582 -63.982,-5.582c-65.288,0 -107.96,39.569 -107.96,111.204l0,62.971l-72.573,0l0,82.621l72.573,0l0,192.915l-191.104,0c-34.524,0 -62.554,-28.03 -62.554,-62.554l0,-386.892c0,-34.524 28.029,-62.554 62.554,-62.554l386.892,0Z"/>
                    </svg>
                </a>

                <!-- Instagram -->
             <a href="https://www.instagram.com/akcentnamestaj/" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" fill="white">
                        <path d="M449.446,0c34.525,0 62.554,28.03 62.554,62.554l0,386.892c0,34.524 -28.03,62.554 -62.554,62.554l-386.892,0c-34.524,0 -62.554,-28.03 -62.554,-62.554l0,-386.892c0,-34.524 28.029,-62.554 62.554,-62.554l386.892,0Zm-193.446,81c-47.527,0 -53.487,0.201 -72.152,1.053c-18.627,0.85 -31.348,3.808 -42.48,8.135c-11.508,4.472 -21.267,10.456 -30.996,20.184c-9.729,9.729 -15.713,19.489 -20.185,30.996c-4.326,11.132 -7.284,23.853 -8.135,42.48c-0.851,18.665 -1.052,24.625 -1.052,72.152c0,47.527 0.201,53.487 1.052,72.152c0.851,18.627 3.809,31.348 8.135,42.48c4.472,11.507 10.456,21.267 20.185,30.996c9.729,9.729 19.488,15.713 30.996,20.185c11.132,4.326 23.853,7.284 42.48,8.134c18.665,0.852 24.625,1.053 72.152,1.053c47.527,0 53.487,-0.201 72.152,-1.053c18.627,-0.85 31.348,-3.808 42.48,-8.134c11.507,-4.472 21.267,-10.456 30.996,-20.185c9.729,-9.729 15.713,-19.489 20.185,-30.996c4.326,-11.132 7.284,-23.853 8.134,-42.48c0.852,-18.665 1.053,-24.625 1.053,-72.152c0,-47.527 -0.201,-53.487 -1.053,-72.152c-0.85,-18.627 -3.808,-31.348 -8.134,-42.48c-4.472,-11.507 -10.456,-21.267 -20.185,-30.996c-9.729,-9.728 -19.489,-15.712 -30.996,-20.184c-11.132,-4.327 -23.853,-7.285 -42.48,-8.135c-18.665,-0.852 -24.625,-1.053 -72.152,-1.053Zm0,31.532c46.727,0 52.262,0.178 70.715,1.02c17.062,0.779 26.328,3.63 32.495,6.025c8.169,3.175 13.998,6.968 20.122,13.091c6.124,6.124 9.916,11.954 13.091,20.122c2.396,6.167 5.247,15.433 6.025,32.495c0.842,18.453 1.021,23.988 1.021,70.715c0,46.727 -0.179,52.262 -1.021,70.715c-0.778,17.062 -3.629,26.328 -6.025,32.495c-3.175,8.169 -6.967,13.998 -13.091,20.122c-6.124,6.124 -11.953,9.916 -20.122,13.091c-6.167,2.396 -15.433,5.247 -32.495,6.025c-18.45,0.842 -23.985,1.021 -70.715,1.021c-46.73,0 -52.264,-0.179 -70.715,-1.021c-17.062,-0.778 -26.328,-3.629 -32.495,-6.025c-8.169,-3.175 -13.998,-6.967 -20.122,-13.091c-6.124,-6.124 -9.917,-11.953 -13.091,-20.122c-2.396,-6.167 -5.247,-15.433 -6.026,-32.495c-0.842,-18.453 -1.02,-23.988 -1.02,-70.715c0,-46.727 0.178,-52.262 1.02,-70.715c0.779,-17.062 3.63,-26.328 6.026,-32.495c3.174,-8.168 6.967,-13.998 13.091,-20.122c6.124,-6.123 11.953,-9.916 20.122,-13.091c6.167,-2.395 15.433,-5.246 32.495,-6.025c18.453,-0.842 23.988,-1.02 70.715,-1.02Zm0,53.603c-49.631,0 -89.865,40.234 -89.865,89.865c0,49.631 40.234,89.865 89.865,89.865c49.631,0 89.865,-40.234 89.865,-89.865c0,-49.631 -40.234,-89.865 -89.865,-89.865Zm0,148.198c-32.217,0 -58.333,-26.116 -58.333,-58.333c0,-32.217 26.116,-58.333 58.333,-58.333c32.217,0 58.333,26.116 58.333,58.333c0,32.217 -26.116,58.333 -58.333,58.333Zm114.416,-151.748c0,11.598 -9.403,20.999 -21.001,20.999c-11.597,0 -20.999,-9.401 -20.999,-20.999c0,-11.598 9.402,-21 20.999,-21c11.598,0 21.001,9.402 21.001,21Z"/>
                    </svg>
                </a>

                <!-- Pinterest -->
         <a href="https://www.pinterest.com/akcentnamestaj/" target="_blank">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" fill="white">
                    <path d="M449.446,0c34.525,0 62.554,28.03 62.554,62.554l0,386.892c0,34.524 -28.03,62.554 -62.554,62.554l-260.214,0c10.837,-18.276 24.602,-44.144 30.094,-65.264c3.331,-12.822 17.073,-65.143 17.073,-65.143c8.934,17.04 35.04,31.465 62.807,31.465c82.652,0 142.199,-76.005 142.199,-170.448c0,-90.528 -73.876,-158.265 -168.937,-158.265c-118.259,0 -181.063,79.384 -181.063,165.827c0,40.192 21.397,90.228 55.623,106.161c5.192,2.415 7.969,1.351 9.164,-3.666c0.909,-3.809 5.53,-22.421 7.612,-31.077c0.665,-2.767 0.336,-5.147 -1.901,-7.86c-11.323,-13.729 -20.394,-38.983 -20.394,-62.536c0,-60.438 45.767,-118.921 123.739,-118.921c67.317,0 114.465,45.875 114.465,111.485c0,74.131 -37.438,125.487 -86.146,125.487c-26.9,0 -47.034,-22.243 -40.579,-49.52c7.727,-32.575 22.696,-67.726 22.696,-91.239c0,-21.047 -11.295,-38.601 -34.673,-38.601c-27.5,0 -49.585,28.448 -49.585,66.551c0,24.27 8.198,40.685 8.198,40.685c0,0 -27.155,114.826 -32.132,136.211c-5.51,23.659 -3.352,56.982 -0.956,78.664l0.011,0.004l-103.993,0c-34.524,0 -62.554,-28.03 -62.554,-62.554l0,-386.892c0,-34.524 28.029,-62.554 62.554,-62.554l386.892,0Z"/>
                </svg>
                </a>
                
                
               

                <!-- LinkedIn -->
           <a href="https://www.linkedin.com/in/yourprofile" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="30" height="30" fill="white">
            <path d="M449.446,0c34.525,0 62.554,28.03 62.554,62.554l0,386.892c0,34.524 -28.03,62.554 -62.554,62.554l-386.892,0c-34.524,0 -62.554,-28.03 -62.554,-62.554l0,-386.892c0,-34.524 28.029,-62.554 62.554,-62.554l386.892,0Zm-288.985,423.278l0,-225.717l-75.04,0l0,225.717l75.04,0Zm270.539,0l0,-129.439c0,-69.333 -37.018,-101.586 -86.381,-101.586c-39.804,0 -57.634,21.891 -67.617,37.266l0,-31.958l-75.021,0c0.995,21.181 0,225.717 0,225.717l75.02,0l0,-126.056c0,-6.748 0.486,-13.492 2.474,-18.315c5.414,-13.475 17.767,-27.434 38.494,-27.434c27.135,0 38.007,20.707 38.007,51.037l0,120.768l75.024,0Zm-307.552,-334.556c-25.674,0 -42.448,16.879 -42.448,39.002c0,21.658 16.264,39.002 41.455,39.002l0.484,0c26.165,0 42.452,-17.344 42.452,-39.002c-0.485,-22.092 -16.241,-38.954 -41.943,-39.002Z"/>
            </svg>
                </a>
      
     
        </div>
        © 2021 Copyright:
        <a class="text-white">Akcent</a>
    </div>

</footer>

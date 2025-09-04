<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?= $title ?> - Wahana Elangcargo Perkasa</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('') ?>assets/img/favicon.png" rel="icon">
  <link href="<?= base_url('') ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('') ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('') ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url('') ?>assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?= base_url('') ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('') ?>assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?= base_url('') ?>assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="<?= base_url('') ?>assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Logis
  * Template URL: https://bootstrapmade.com/logis-bootstrap-logistics-website-template/
  * Updated: Jun 29 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="<?= base_url('/'); ?>" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="<?= base_url('') ?>assets/img/logo.png" alt=""> -->
        <h1 class="sitename">WEP Cargo</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/" class="active">Home<br></a></li>
          <li><a href="/pages/about">About</a></li>
          <li><a href="/pages/services">Services</a></li>
          <!-- <li><a href="pricing.html">Pricing</a></li> -->
          <!-- <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li> -->
          <li><a href="/pages/contact">Contact</a></li>
          <li><a href="/pages/login">Login</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="btn-getstarted" href="https://wa.me/6285175295353" target="_blank">Get a Quote</a>
    </div>
  </header>

  <main class="main">
  <?= $this->renderSection('content') ?>
  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="/" class="logo d-flex align-items-center">
            <span class="sitename">WEP Cargo</span>
          </a>
          <p>Jl. Jatinegara Barat No. 160, Desa/Kelurahan Kampung melayu, Kec. Jatinegara, Kota Adm. Jakarta Timur, Provinsi DKI Jakarta. Kode Poas: 13320</p>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Storage</a></li>
            <li><a href="#">Logistics</a></li>
            <li><a href="#">Cargo</a></li>
            <li><a href="#">Trucking</a></li>
            <li><a href="#">Packaging</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
          <h4>Contact Us</h4>
          <p>Wisma Setia Ciliwung Blok A 108</p>
          <p>Jl. Bukit Duri Tanjakan No. 54</p>
          <p>Jakarta Selatan</p>
          <p class="mt-4"><strong>Phone:</strong> <span>(021) 831 4968</span></p>
          <p><strong>Email:</strong> <span>wepcargo@gmail.com</span></p>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Logis</strong> <span>All Rights Reserved</span></p>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="<?= base_url('') ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('') ?>assets/vendor/php-email-form/validate.js"></script>
  <script src="<?= base_url('') ?>assets/vendor/aos/aos.js"></script>
  <script src="<?= base_url('') ?>assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="<?= base_url('') ?>assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?= base_url('') ?>assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="<?= base_url('') ?>assets/js/main.js"></script>

</body>

</html>
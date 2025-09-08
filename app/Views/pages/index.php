<?= $this->extend('layout/front_template'); ?>
<?= $this->section('content'); ?>
<!-- Hero Section -->
<section id="hero" class="hero section dark-background">

  <img src="<?= base_url('') ?>assets/img/world-dotted-map.png" alt="" class="hero-bg" data-aos="fade-in">

  <div class="container">
    <div class="row gy-4 d-flex justify-content-between">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
        <h2 data-aos="fade-up"><?= getenv('COMPANY_NAME'); ?></h2>
        <p data-aos="fade-up" data-aos-delay="100">DOMESTIC AND INTERNATIONAL CARGO SERVICE</p>

        <form action="<?= base_url('tracking-package'); ?>" method="post" class="form-search d-flex align-items-stretch mb-3" data-aos="fade-up" data-aos-delay="200">
          <?= csrf_field() ?>
          <input type="text" name="no_resi" class="form-control" placeholder="Your Airwaibill Number" autocomplete="off" required>
          <button type="submit" class="btn btn-primary">Track Now</button>
        </form>
      </div>

      <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
        <img src="<?= base_url('') ?>assets/img/hero-img.svg" class="img-fluid mb-3 mb-lg-0" alt="">
      </div>

    </div>
  </div>

</section><!-- /Hero Section -->

<!-- Featured Services Section -->
<section id="featured-services" class="featured-services section">

  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="100">
        <div class="icon flex-shrink-0"><i class="fa-solid fa-cart-flatbed"></i></div>
        <div>
          <h4 class="title">Logistik Terintegrasi</h4>
          <p class="description">Kami menyediakan layanan pengiriman barang dengan kecepatan dan keandalan yang tinggi, memastikan paket Anda tiba tepat waktu.</p>
          <a href="#" class="readmore stretched-link"><span>Learn More</span><i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <!-- End Service Item -->

      <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="200">
        <div class="icon flex-shrink-0"><i class="fa-solid fa-truck"></i></div>
        <div>
          <h4 class="title">Layanan Pengiriman</h4>
          <p class="description">Kami menyediakan layanan pengiriman barang dengan kecepatan dan keandalan yang tinggi, memastikan paket Anda tiba tepat waktu.</p>
          <a href="#" class="readmore stretched-link"><span>Learn More</span><i class="bi bi-arrow-right"></i></a>
        </div>
      </div><!-- End Service Item -->

      <div class="col-lg-4 col-md-6 service-item d-flex" data-aos="fade-up" data-aos-delay="300">
        <div class="icon flex-shrink-0"><i class="fa-solid fa-truck-ramp-box"></i></div>
        <div>
          <h4 class="title">Pelacakan Realtime</h4>
          <p class="description">Fasilitas pelacakan real-time untuk memonitor status pengiriman Anda, memberikan ketenangan pikiran dan transparansi penuh.</p>
          <a href="#" class="readmore stretched-link"><span>Learn More</span><i class="bi bi-arrow-right"></i></a>
        </div>
      </div><!-- End Service Item -->

    </div>

  </div>

</section><!-- /Featured Services Section -->

<!-- About Section -->
<section id="about" class="about section">

  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-6 position-relative align-self-start order-lg-last order-first" data-aos="fade-up" data-aos-delay="200">
        <img src="<?= base_url('') ?>assets/img/about.jpg" class="img-fluid" alt="">
        <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox pulsating-play-btn"></a>
      </div>

      <div class="col-lg-6 content order-last  order-lg-first" data-aos="fade-up" data-aos-delay="100">
        <h3>About Us</h3>
        <p>
          Kami adalah perusahaan pengiriman barang yang berkomitmen untuk memberikan layanan terbaik kepada pelanggan kami. Dengan pengalaman bertahun-tahun di industri logistik, kami memahami betapa pentingnya kecepatan dan keamanan dalam setiap pengiriman. Kami terus berinovasi dan menggunakan teknologi terkini untuk memastikan bahwa setiap paket yang Anda kirimkan bersama kami tiba tepat waktu dan dalam kondisi sempurna.
        </p>
        <ul>
          <li>
            <i class="bi bi-diagram-3"></i>
            <div>
              <h5>Solusi Logistik Terintegrasi</h5>
              <p>Kami menawarkan solusi logistik menyeluruh yang mencakup penyimpanan, penanganan, dan pengiriman barang dengan efisiensi tinggi.</p>
            </div>
          </li>
          <li>
            <i class="bi bi-fullscreen-exit"></i>
            <div>
              <h5>Pengiriman Cepat dan Aman</h5>
              <p>Layanan pengiriman kami menjamin keamanan dan kecepatan, dengan dukungan teknologi pelacakan terkini untuk memastikan paket Anda sampai dengan aman.</p>
            </div>
          </li>
          <li>
            <i class="bi bi-broadcast"></i>
            <div>
              <h5>Jaringan Distribusi Luas</h5>
              <p>Dengan jaringan distribusi yang luas dan tim profesional, kami siap memenuhi semua kebutuhan pengiriman Anda, baik lokal maupun internasional.</p>
            </div>
          </li>
        </ul>
      </div>

    </div>

  </div>

</section><!-- /About Section -->

<!-- Services Section -->
<section id="services" class="services section">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <span>Our Services<br></span>
    <h2>Our ServiceS</h2>
    <p>Kami menawarkan berbagai layanan pengiriman yang cepat, aman, dan terpercaya untuk memenuhi kebutuhan logistik Anda.</p>
  </div><!-- End Section Title -->

  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="card">
          <div class="card-img">
            <img src="<?= base_url('') ?>assets/img/service-1.jpg" alt="" class="img-fluid">
          </div>
          <h3>Storage</h3>
          <p>Dengan layanan storage kami, Anda dapat memastikan bahwa barang Anda disimpan dengan aman dan efisien, sehingga Anda dapat fokus pada kegiatan bisnis utama Anda. Percayakan kebutuhan penyimpanan Anda kepada kami dan nikmati layanan yang profesional dan terpercaya.</p>
        </div>
      </div><!-- End Card Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="card">
          <div class="card-img">
            <img src="<?= base_url('') ?>assets/img/service-2.jpg" alt="" class="img-fluid">
          </div>
          <h3><a href="#" class="stretched-link">Logistics</a></h3>
          <p>Dengan layanan logistics kami, Anda dapat mengandalkan solusi logistik yang handal dan efisien, memungkinkan bisnis Anda berjalan lebih lancar dan efektif. Kami berkomitmen untuk memberikan layanan terbaik dan memastikan kepuasan pelanggan dalam setiap pengiriman.</p>
        </div>
      </div><!-- End Card Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="card">
          <div class="card-img">
            <img src="<?= base_url('') ?>assets/img/service-3.jpg" alt="" class="img-fluid">
          </div>
          <h3><a href="#" class="stretched-link">Cargo</a></h3>
          <p>Layanan cargo dari kami menawarkan solusi pengiriman barang yang efisien dan terpercaya, baik untuk pengiriman domestik maupun internasional.</p>
        </div>
      </div><!-- End Card Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
        <div class="card">
          <div class="card-img">
            <img src="<?= base_url('') ?>assets/img/service-4.jpg" alt="" class="img-fluid">
          </div>
          <h3><a href="#" class="stretched-link">Trucking</a></h3>
          <p>Layanan trucking dari kami menyediakan solusi transportasi darat yang handal dan efisien untuk pengiriman barang Anda. Dengan armada truk yang modern dan beragam, kami mampu menangani berbagai jenis muatan, mulai dari kargo kecil hingga besar.</p>
        </div>
      </div><!-- End Card Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
        <div class="card">
          <div class="card-img">
            <img src="<?= base_url('') ?>assets/img/service-5.jpg" alt="" class="img-fluid">
          </div>
          <h3>Packaging</h3>
          <p>Layanan packaging dari kami menawarkan solusi pengemasan yang aman dan efisien untuk melindungi barang Anda selama pengiriman. Kami menggunakan bahan berkualitas tinggi dan teknik pengemasan modern untuk memastikan setiap item terlindungi dari kerusakan.</p>
        </div>
      </div><!-- End Card Item -->

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
        <div class="card">
          <div class="card-img">
            <img src="<?= base_url('') ?>assets/img/service-6.jpg" alt="" class="img-fluid">
          </div>
          <h3><a href="#" class="stretched-link">Warehousing</a></h3>
          <p>Layanan warehousing dari kami menyediakan solusi penyimpanan yang aman dan terkelola dengan baik untuk barang Anda. Fasilitas kami dilengkapi dengan teknologi canggih dan sistem manajemen inventori yang efisien untuk memastikan pengelolaan stok yang optimal.</p>
        </div>
      </div>
      <!-- End Card Item -->

    </div>

  </div>

</section>
<!-- /Services Section -->

<!-- Call To Action Section -->
<section id="call-to-action" class="call-to-action section dark-background">

  <img src="<?= base_url('') ?>assets/img/cta-bg.jpg" alt="">

  <div class="container">
    <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
      <div class="col-xl-10">
        <div class="text-center">
          <h3>Hubungi Kami</h3>
          <p>Ingin mengetahui biaya pengiriman terbaik untuk kebutuhan Anda? Hubungi kami hari ini untuk mendapatkan penawaran gratis dan solusi yang sesuai untuk bisnis Anda</p>
          <a class="cta-btn" href="https://wa.me/6285175295353" target="_blank">Hubungi Kami</a>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /Call To Action Section -->

<!-- Section features -->
<!-- <section id="features" class="features section">

      <div class="container section-title" data-aos="fade-up">
        <span>Features</span>
        <h2>Features</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>

      <div class="container">

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="100">
            <img src="<?= base_url('') ?>assets/img/features-1.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">
            <h3>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>
            <p class="fst-italic">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.
            </p>
            <ul>
              <li><i class="bi bi-check"></i><span> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
              <li><i class="bi bi-check"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
              <li><i class="bi bi-check"></i> <span>Ullam est qui quos consequatur eos accusamus.</span></li>
            </ul>
          </div>
        </div>

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 order-1 order-md-2 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
            <img src="<?= base_url('') ?>assets/img/features-2.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-md-7 order-2 order-md-1" data-aos="fade-up" data-aos-delay="200">
            <h3>Corporis temporibus maiores provident</h3>
            <p class="fst-italic">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.
            </p>
            <p>
              Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
              velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
              culpa qui officia deserunt mollit anim id est laborum
            </p>
          </div>
        </div>

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 d-flex align-items-center" data-aos="zoom-out">
            <img src="<?= base_url('') ?>assets/img/features-3.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-md-7" data-aos="fade-up">
            <h3>Sunt consequatur ad ut est nulla consectetur reiciendis animi voluptas</h3>
            <p>Cupiditate placeat cupiditate placeat est ipsam culpa. Delectus quia minima quod. Sunt saepe odit aut quia voluptatem hic voluptas dolor doloremque.</p>
            <ul>
              <li><i class="bi bi-check"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
              <li><i class="bi bi-check"></i><span> Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
              <li><i class="bi bi-check"></i> <span>Facilis ut et voluptatem aperiam. Autem soluta ad fugiat</span>.</li>
            </ul>
          </div>
        </div>

        <div class="row gy-4 align-items-center features-item">
          <div class="col-md-5 order-1 order-md-2 d-flex align-items-center" data-aos="zoom-out">
            <img src="<?= base_url('') ?>assets/img/features-4.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-md-7 order-2 order-md-1" data-aos="fade-up">
            <h3>Quas et necessitatibus eaque impedit ipsum animi consequatur incidunt in</h3>
            <p class="fst-italic">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
              magna aliqua.
            </p>
            <p>
              Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
              velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
              culpa qui officia deserunt mollit anim id est laborum
            </p>
          </div>
        </div>

      </div>

    </section> -->

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials section dark-background">

  <img src="<?= base_url('') ?>assets/img/testimonials-bg.jpg" class="testimonials-bg" alt="">

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="swiper init-swiper">
      <script type="application/json" class="swiper-config">
        {
          "loop": true,
          "speed": 600,
          "autoplay": {
            "delay": 5000
          },
          "slidesPerView": "auto",
          "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets",
            "clickable": true
          }
        }
      </script>
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="<?= base_url('') ?>assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
            <h3>Saul Goodman</h3>
            <h4>Ceo &amp; Founder</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="<?= base_url('') ?>assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
            <h3>Sara Wilsson</h3>
            <h4>Designer</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="<?= base_url('') ?>assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
            <h3>Jena Karlis</h3>
            <h4>Store Owner</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="<?= base_url('') ?>assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
            <h3>Matt Brandon</h3>
            <h4>Freelancer</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="<?= base_url('') ?>assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
            <h3>John Larson</h3>
            <h4>Entrepreneur</h4>
            <div class="stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p>
              <i class="bi bi-quote quote-icon-left"></i>
              <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
              <i class="bi bi-quote quote-icon-right"></i>
            </p>
          </div>
        </div><!-- End testimonial item -->

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>

</section><!-- /Testimonials Section -->
<?= $this->endSection() ?>
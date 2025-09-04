<?= $this->extend('layout/front_template'); ?>
<?= $this->section('content'); ?>
<!-- Page Title -->
<div class="page-title dark-background" data-aos="fade" style="background-image: url(<?= base_url('') ?>assets/img/page-title-bg.jpg);">
  <div class="container position-relative">
    <h1>Services</h1>
    <p>Kami menawarkan berbagai layanan pengiriman yang cepat, aman, dan terpercaya untuk memenuhi kebutuhan logistik Anda.</p>
    <nav class="breadcrumbs">
      <ol>
        <li><a href="/">Home</a></li>
        <li class="current">Services</li>
      </ol>
    </nav>
  </div>
</div><!-- End Page Title -->

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

<?= $this->endSection() ?>
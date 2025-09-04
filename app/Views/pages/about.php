<?= $this->extend('layout/front_template'); ?>
<?= $this->section('content'); ?>
<!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(<?= base_url('') ?>assets/img/page-title-bg.jpg);">
      <div class="container position-relative">
        <h1>About</h1>
        <p>Kami menawarkan berbagai layanan pengiriman yang cepat, aman, dan terpercaya untuk memenuhi kebutuhan logistik Anda.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="/">Home</a></li>
            <li class="current">About</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

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

<?= $this->endSection() ?>
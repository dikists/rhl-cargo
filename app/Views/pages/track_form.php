<?= $this->extend('layout/front_template'); ?>
<?= $this->section('content'); ?>
<!-- Page Title -->
<div class="page-title dark-background" data-aos="fade" style="background-image: url(<?= base_url('') ?>assets/img/page-title-bg.jpg);">
     <div class="container position-relative">
          <h1>Pelacakan</h1>
          <nav class="breadcrumbs">
               <ol>
                    <li><a href="/">Home</a></li>
                    <li class="current">Tracking</li>
               </ol>
          </nav>
     </div>
</div><!-- End Page Title -->

<!-- Contact Section -->
<section id="hero" class="hero section light-background">

     <!-- <img src="<?= base_url('') ?>assets/img/world-dotted-map.png" alt="" class="hero-bg" data-aos="fade-in"> -->

     <div class="container">
          <div class="row gy-4 d-flex justify-content-between">
               <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                    <h2 data-aos="fade-up">WAHANA ELANGCARGO PERKASA</h2>
                    <p data-aos="fade-up" data-aos-delay="100">DOMESTIC AND INTERNATIONAL CARGO SERVICE</p>

                    <form action="<?= base_url('tracking-package'); ?>" method="post" class="form-search d-flex align-items-stretch mb-3" data-aos="fade-up" data-aos-delay="200">
                         <?= csrf_field() ?>
                         <input type="text" name="no_resi" class="form-control" value="<?= set_value('no_resi') ?>" placeholder="Your Airwaibill Number" autocomplete="off">
                         <button type="submit" class="btn btn-primary">Track Now</button>
                    </form>
               </div>

               <div class="col-lg-5 order-1 order-lg-2 maskot01" data-aos="zoom-out">
                    <img src="<?= base_url('') ?>assets/img/maskot01.svg" class="img-fluid mb-3 mb-lg-0" alt="">
               </div>

          </div>
          <?php if (session()->getFlashdata('error')) : ?>
               <div class="alert alert-warning">
                    <?= session()->getFlashdata('error') ?>
               </div>
          <?php endif; ?>
     </div>

</section>

<?= $this->endSection() ?>
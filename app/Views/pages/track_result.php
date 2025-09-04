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

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-4 d-flex justify-content-between">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
        <h2 data-aos="fade-up">WAHANA ELANGCARGO PERKASA</h2>
        <p data-aos="fade-up" data-aos-delay="100">DOMESTIC AND INTERNATIONAL CARGO SERVICE</p>

        <form action="<?= base_url('tracking-package'); ?>" method="post" class="form-search d-flex align-items-stretch mb-3" data-aos="fade-up" data-aos-delay="200">
          <?= csrf_field() ?>
          <input type="text" name="no_resi" class="form-control" value="<?= $no_resi ?>" placeholder="Your Airwaibill Number" autocomplete="off">
          <button type="submit" class="btn btn-primary">Track Now</button>
        </form>
      </div>

      <div class="col-lg-5 order-1 order-lg-2 maskot01" data-aos="zoom-out">
        <img src="<?= base_url('') ?>assets/img/maskot01.svg" class="img-fluid mb-3 mb-lg-0" alt="">
      </div>

    </div>

    <h2>Hasil Tracking untuk Resi: <?= $no_resi ?></h2>

    <table class="table table-bordered mt-3">
      <tr>
        <th>Pengirim</th>
        <td><?= $tracking->nama_pelanggan ?></td>
      </tr>
      <tr>
        <th>Penerima</th>
        <td><?= $tracking->nama_penerima ?></td>
      </tr>
      <tr>
        <th>Asal</th>
        <td><?= $tracking->kota ?></td>
      </tr>
      <tr>
        <th>Tujuan</th>
        <td><?= $tracking->kota_penerima ?></td>
      </tr>
      <tr>
        <th>Tanggal Pengiriman</th>
        <td><?= date('d M Y', strtotime($tracking->tanggal_order)) ?></td>
      </tr>
      <tr>
        <th>Status</th>
        <td><?= (!empty($status[0]['status_name'])) ? $status[0]['status_name'] : 'On Process'; ?></td>
      </tr>
    </table>

    <h3>Riwayat Tracking</h3>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Lokasi</th>
          <th>Status</th>
        </tr>
      </thead>
      <t>
        <tr>
          <td><i class="fas fa-clock"></i> <?= date('d F, Y', strtotime($tracking->tanggal_order)); ?></td>
          <td><i class="fas fa-map"></i> <?= $tracking->kota ?></td>
          <td><?= strtoupper('document is created'); ?></td>
        </tr>
        <tr>
          <td><i class="fas fa-clock"></i> <?= date('d F, Y', strtotime($tracking->tgl_pembuatan)); ?> <?= date('H:i:s', strtotime($tracking->waktu_pembuatan)); ?></td>
          <td><i class="fas fa-map"></i> <?= $tracking->kota ?></td>
          <td><?= strtoupper('goods pickup process'); ?></td>
        </tr>
        <?php $tampil_ambil = ($tracking->tanggal_ambil) ? '' : 'none'; ?>
        <tr style="display:<?= $tampil_ambil; ?>">
          <td>
            <i class="fas fa-clock"></i>
            <?= date('d F, Y', strtotime($tracking->tanggal_ambil)); ?>
            <?= date('H:i:s', strtotime($tracking->waktu_ambil . ' -5 hours')); ?>
          </td>
          <td><i class="fas fa-map"></i> Jakarta</td>
          <td><?= strtoupper('received at the shorting center'); ?></td>
        </tr>
        <?php $tampil_kirim = ($tracking->tanggal_kirim) ? '' : 'none'; ?>
        <tr style="display:<?= $tampil_kirim; ?>">
          <td>
            <i class="fas fa-clock"></i> 
            <?= date('d F, Y', strtotime($tracking->tanggal_kirim)); ?> 
            <?= date('H:i:s', strtotime($tracking->waktu_kirim. ' +5 hours')); ?></td>
          <td><i class="fas fa-map"></i> Jakarta</td>
          <td><?= strtoupper('departed from transit center'); ?></td>
        </tr>
        <?php if (!empty($status)) : ?>
          <?php foreach ($status as $index => $item) : ?>
            <?php if ($item['status_name'] == 'Delivered') : ?>
              <tr>
                <td><i class="fas fa-clock"></i> <?php echo date('d F, Y H:i:s', strtotime($item['changed_at'])); ?></td>
                <td><i class="fas fa-map"></i> <?= $tracking->kota_penerima ?></td>
                <td><?= strtoupper($item['status_name']); ?> <br> (<?= strtoupper($tracking->dto) ?>)</td>
              </tr>
            <?php else: ?>
              <tr>
                <td><i class="fas fa-clock"></i> <?php echo date('d F, Y H:i:s', strtotime($item['changed_at'])); ?></td>
                <td>-</td>
                <td><?= strtoupper($item['status_name']); ?></td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
  </div>

</section><!-- /Contact Section -->

<?= $this->endSection() ?>
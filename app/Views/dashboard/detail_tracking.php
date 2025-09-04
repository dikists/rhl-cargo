<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tracking Surat Jalan : <?= $tracking->no_surat_jalan ?></h1>
    <a href="/tracking" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm text-dark"><i class="fas fa-arrow-left fa-sm text-dark"></i> Kembali</a>
  </div>

  <!-- Content Row -->
  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <ul class="timeline">
            <li>
              <div class="timeline-badge"><i class="fas fa-check"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title"><?= strtoupper('document is created'); ?></h4>
                  <p class="text-dark font-weight-bold"><i class="fas fa-clock"></i> <?= date('d F, Y', strtotime($tracking->tanggal_order)); ?></p>
                </div>
                <div class="timeline-body">
                  <p>Order telah diterima oleh sistem kami.</p>
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge"><i class="fas fa-tasks"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title"><?= strtoupper('goods pickup process'); ?></h4>
                  <p class="text-dark font-weight-bold"><i class="fas fa-clock"></i> <?= date('d F, Y', strtotime($tracking->tgl_pembuatan)); ?> <?= date('h:i:s', strtotime($tracking->waktu_pembuatan)); ?></p>
                </div>
                <div class="timeline-body">
                  <?= $tracking->kota ?>
                  <p>Order Anda sedang diproses..</p>
                </div>
              </div>
            </li>

            <?php $tampil_ambil = ($tracking->tanggal_ambil) ? '' : 'none'; ?>
            <li style="display:<?= $tampil_ambil; ?>">
              <div class="timeline-badge"><i class="fas fa-cubes"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title"><?= strtoupper('received at the shorting center'); ?></h4>
                  <p class="text-dark font-weight-bold"><i class="fas fa-clock"></i> <?= date('d F, Y', strtotime($tracking->tanggal_ambil)); ?> <?= date('h:i:s', strtotime($tracking->waktu_ambil)); ?></p>
                </div>
                <div class="timeline-body">
                  <?= $tracking->kota ?>
                  <p>Barang telah masuk di pusat shorting</p>
                </div>
              </div>
            </li>

            <?php $tampil_kirim = ($tracking->tanggal_kirim) ? '' : 'none'; ?>
            <li class="timeline-inverted" style="display:<?= $tampil_kirim; ?>">
              <div class="timeline-badge"><i class="fas fa-truck"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title"><?= strtoupper('departed from transit center'); ?></h4>
                  <p class="text-dark font-weight-bold"><i class="fas fa-clock"></i> <?= date('d F, Y', strtotime($tracking->tanggal_kirim)); ?> <?= date('h:i:s', strtotime($tracking->waktu_kirim)); ?></p>
                </div>
                <div class="timeline-body">
                  <?= $tracking->kota ?>
                  <p>Barang telah berangkat dari pusat transit</p>
                </div>
              </div>
            </li>
            <?php if (!empty($status)) : ?>
              <?php foreach ($status as $index => $item) : ?>
                <li class="<?= ($index + 1) % 2 == 0 ? 'timeline-inverted' : '' ?>">
                  <div class="timeline-badge"><i class="fas fa-check"></i></div>
                  <div class="timeline-panel">
                    <div class="timeline-heading">
                      <h4 class="timeline-title"><?= strtoupper($item['status_name']); ?></h4>
                      <p class="text-dark font-weight-bold"><i class="fas fa-clock"></i> <?php echo date('d F, Y H:i:s', strtotime($item['changed_at'])); ?></p>
                    </div>
                    <?php if ($item['status_name'] == 'Delivered') : ?>
                      <div class="timeline-body">
                        <p> <?= $tracking->kota_penerima ?>
                          <br> Diterima Oleh : <?= strtoupper($tracking->dto) ?>
                        </p>
                      </div>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-success shadow h-100 py-2">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
          <h6 class="m-0 font-weight-bold text-black">Detail Barang</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Jumlah</th>
                  <th scope="col">Berat</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $totalKg = $totalJumlah = 0;
                ?>
                <?php
                foreach ($detail as $d) :
                $totalJumlah += $d['jumlah'];
                $totalKg += (($d['berat'] > ceil(($d['volume'] / $d['divider'])) ? $d['berat'] : ceil(($d['volume'] / $d['divider'])))) * $d['jumlah'];
                ?>
                  <tr>
                    <th scope="row"><?= $no++ ?></th>
                    <td><?= $d['barang'] ?></td>
                    <td><?= $d['jumlah'] ?> <?= $d['satuan'] ?></td>
                    <td><?= (($d['berat'] > ceil(($d['volume'] / $d['divider'])) ? $d['berat'] : ceil(($d['volume'] / $d['divider'])))) * $d['jumlah']; ?> KG</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot class="bg-success text-white">
                <th colspan="2" class="text-center">Total</th>
                <th><?= $totalJumlah ?></th>
                <th><?= $totalKg ?> KG</th>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.container-fluid -->

<?= $this->endSection(); ?>
<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <h1 class="h3 m-4 text-gray-800 text-center"><?= $title; ?> No Resi : <?= $pengiriman['no_surat_jalan']; ?></h1>
     <h2 class="m-4 text-gray-800 text-center"><?= $pengiriman['shipper']; ?> - <?= $pengiriman['consignee']; ?></h2>

     <div class="row mb-3">
          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">
               <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                         <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>
                         <a href="<?= base_url('admin/pengiriman'); ?>" class="btn btn-warning btn-sm text-dark">
                              <i class="fas fa-arrow-left"></i> Kembali
                         </a>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <?php if (session()->getFlashdata('success')): ?>
                              <div class="alert alert-success">
                                   <?= session()->getFlashdata('success') ?>
                              </div>
                         <?php endif; ?>

                         <?php
                         $tanggal_order             = (empty($pengiriman['tanggal_order'])) ? '' : date_format(date_create($pengiriman['tanggal_order']), "Y-m-d");
                         $tanggal_ambil             = (empty($pengiriman['tanggal_ambil'])) ? '' : date_format(date_create($pengiriman['tanggal_ambil']), "Y-m-d");
                         $waktu_ambil               = (empty($pengiriman['waktu_ambil'])) ? '' : date_format(date_create($pengiriman['waktu_ambil']), "H:i:s");
                         $tanggal_kirim             = (empty($pengiriman['tanggal_kirim'])) ? '' : date_format(date_create($pengiriman['tanggal_kirim']), "Y-m-d");
                         $tanggal_terima            = (empty($pengiriman['tanggal_terima'])) ? '' : date_format(date_create($pengiriman['tanggal_terima']), "Y-m-d");
                         $waktu_terima              = (empty($pengiriman['waktu_terima'])) ? date('H:i:s') : date_format(date_create($pengiriman['waktu_terima']), "H:i:s");
                         ?>
                         <form action="<?= base_url('delivery-status'); ?>" method="POST">
                              <?= @csrf_field(); ?>
                              <div class="row">
                                   <div class="col-3 form-group">
                                        <label> Tanggal Order </label>
                                        <input type="text" class="form-control" name="tanggal_order" value="<?= $tanggal_order; ?>" id="tanggal_order" readonly>
                                   </div>
                                   <div class="col-3 form-group">
                                        <label> Tanggal Ambil </label>
                                        <input type="text" class="form-control datepicker" name="tanggal_ambil" value="<?= $tanggal_ambil; ?>" id="tanggal_ambil" autocomplete="off">
                                   </div>
                                   <div class="form-group col-md-3">
								<label for="jam_ambil">Jam Ambil</label>
								<input type="text" class="form-control timepicker" name="jam_ambil" id="jam_ambil" value="<?= $waktu_ambil; ?>" autocomplete="off">
							</div>
                                   <div class="col-lg-3">
                                        <div class="form-group">
                                             <label> Tanggal Kirim </label>
                                             <input type="text" class="form-control datepicker" name="tanggal_kirim" id="tanggal_kirim" value="<?= $tanggal_kirim; ?>" autocomplete="off">
                                        </div>
                                   </div>
                                   <div class="col-lg-3">
                                        <div class="form-group">
                                             <label> Tanggal Terima </label>
                                             <input type="text" class="form-control datepicker" name="tanggal_terima" id="" value="<?= $tanggal_terima; ?>" autocomplete="off">
                                        </div>
                                   </div>
                                   <div class="form-group col-md-3">
								<label for="jam_terima">Jam Terima</label>
								<input type="text" class="form-control timepicker" name="jam_terima" id="jam_terima" value="<?= $waktu_terima; ?>" autocomplete="off">
							</div>
                                   <div class="col-lg-3">
                                        <label for="dto">DTO</label>
                                        <input type="text" class="form-control" id="dto" name="dto" value="<?= $pengiriman['dto']; ?>">
                                   </div>
                              </div>
                              <input type="hidden" name="id_pengiriman" value="<?= $pengiriman['id_pengiriman']; ?>">
                              <input type="hidden" name="id_pengambilan" value="<?= $pengiriman['id_pengambilan']; ?>">
                              <input type="hidden" name="id_order" value="<?= $pengiriman['id_order']; ?>">
                              <button type="submit" class="btn btn-primary mt-3">
                                   Update
                              </button>
                         </form>
                    </div>
               </div>
          </div>
     </div>
</div>

<?= $this->endSection(); ?>
<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
     </div>

     <div class="row mb-3">

          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">
               <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                         <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>

                         <div class="float-right">
                              <a href="<?= base_url('admin/offers'); ?>" class="btn btn-warning text-dark"><i class="fas fa-arrow-left"></i> Kembali</a>
                              <a href="<?= base_url('admin/offers/add'); ?>" class="btn btn-success btn-sm">
                                   <i class="fas fa-plus"></i> Tambah data
                              </a>
                         </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <?php if (session()->getFlashdata('success')): ?>
                              <div class="alert alert-success">
                                   <?= session()->getFlashdata('success') ?>
                              </div>
                         <?php endif; ?>
                         <!-- FORM TAMBAH -->
                         <form action="<?= base_url('admin/offers') ?>" method="post">
                              <?= csrf_field() ?>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="pelanggan">Pelanggan</label>
                                        <select id="pelanggan_id" name="pelanggan_id" class="form-control mySelect2" required>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($pelanggan as $data) : ?>
                                                  <option value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="offer_date" class="form-label">Tanggal</label>
                                        <input type="text" class="form-control datepicker" id="offer_date" name="offer_date" required autocomplete="off" value="<?= date('Y-m-d'); ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="offer_text_title" class="form-label">Perihal</label>
                                        <textarea class="form-control" id="offer_text_title" name="offer_text_title">Penawaran Jasa Ekspedisi </textarea>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="offer_text_opening" class="form-label">Kata Pembuka</label>
                                        <textarea class="form-control" id="offer_text_opening" name="offer_text_opening">Bersama ini kami dari  <?= getenv('COMPANY_NAME'); ?> memberitahukan adanya penyesuaian jasa ekspedisi sbb :</textarea>
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="offer_clause_desc" class="form-label">Klausal</label>
                                        <select id="offer_clause_desc" name="offer_clause_desc" class="form-control mySelect2" required>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($clause as $data) : ?>
                                                  <option value="<?= $data['clause_id']; ?>"><?= $data['clause_name']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>

                              <button type="submit" class="btn btn-primary">Simpan</button>
                         </form>

                    </div>
               </div>
          </div>
     </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCetakLabel" tabindex="-1" aria-labelledby="modalCetakLabelLabel" aria-hidden="true">
     <div class="modal-dialog">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalCetakLabelLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <form>
                         <div class="form-group">
                              <label for="qtyCetak">Qty</label>
                              <input type="text" class="form-control" id="qtyCetak" autocomplete="off">
                              <input type="hidden" class="form-control" id="idSJ" autocomplete="off">
                         </div>
                    </form>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="cetakLabelPdf">Cetak</button>
               </div>
          </div>
     </div>
</div>


<?= $this->endSection(); ?>
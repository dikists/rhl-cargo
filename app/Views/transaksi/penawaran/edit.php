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
                         <form action="<?= base_url('admin/offers/'.$offer['id']) ?>" method="post">
                              <?= csrf_field() ?>
                              <input type="hidden" name="_method" value="PUT">
                              <input type="text" name="id_offer" id="id_offer" value="<?= $offer['id']; ?>" hidden required class="form-control">
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="pelanggan">Pelanggan</label>
                                        <select id="pelanggan_id" name="pelanggan_id" class="form-control mySelect2" required>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($pelanggan as $data) : ?>
                                                  <option <?= $offer['pelanggan_id'] == $data['id_pelanggan'] ? 'selected' : ''; ?> value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="offer_date" class="form-label">Tanggal</label>
                                        <input type="text" class="form-control datepicker" id="offer_date" name="offer_date" required autocomplete="off" value="<?= $offer['offer_date'] ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="offer_text_title" class="form-label">Perihal</label>
                                        <textarea class="form-control" id="offer_text_title" name="offer_text_title"><?= $offer['offer_text_title']; ?> </textarea>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="offer_text_opening" class="form-label">Kata Pembuka</label>
                                        <textarea class="form-control" id="offer_text_opening" name="offer_text_opening"><?= $offer['offer_text_opening']; ?>:</textarea>
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="offer_clause_desc" class="form-label">Klausal</label>
                                        <select id="offer_clause_desc" name="offer_clause_desc" class="form-control mySelect2">
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($clause as $data) : ?>
                                                  <option value="<?= $data['clause_id']; ?>"><?= $data['clause_name']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="offer_clause_desc_now" class="form-label">Klausal Saat Ini :</label>
                                        <?= $offer['offer_clause_desc']; ?>
                                   </div>
                              </div>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                         </form>

                    </div>
               </div>
          </div>
     </div>
</div>


<?= $this->endSection(); ?>
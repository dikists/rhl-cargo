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
                         <h5 class="m-0 font-weight-bold text-black"><?= $title; ?> <?= $invoice['invoice_number']; ?></h5>

                         <div class="float-right">
                              <a href="<?= base_url('admin/invoice'); ?>" class="btn btn-warning text-dark"><i class="fas fa-arrow-left"></i> Kembali</a>
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
                         <form action="<?= base_url('admin/invoice/' . $invoice['id'] . '') ?>" method="post">
                              <?= csrf_field() ?>
                              <input type="hidden" name="_method" value="PUT">
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="pelanggan">Bill To</label>
                                        <select id="pelanggan_id" name="pelanggan_id" class="form-control mySelect2" required>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($pelanggan as $data) : ?>
                                                  <option <?= $invoice['id_pelanggan'] == $data['id_pelanggan'] ? 'selected' : ''; ?> value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="issue_date" class="form-label">Tanggal</label>
                                        <input type="text" class="form-control datepicker" id="issue_date" name="issue_date" required autocomplete="off" value="<?= $invoice['issue_date']; ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="tax_invoice_number" class="form-label">Nomor Faktur</label>
                                        <input type="text" class="form-control" id="tax_invoice_number" name="tax_invoice_number" placeholder="Nomor Faktur" autocomplete="off" value="<?= $invoice['tax_invoice_number']; ?>">
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="no_rekening" class="form-label">Nomor Rekening</label>
                                        <select name="no_rekening" id="no_rekening" class="form-control mySelect2">
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($rekening as $data) : ?>
                                                  <option <?= $invoice['account_id'] == $data['id'] ? 'selected' : ''; ?> value="<?= $data['id']; ?>"><?= $data['account_holder_name']; ?> (<?= $data['account_number']; ?>)</option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="rtax" class="form-label">Kategori Penagihan PPN</label>
                                        <select name="rtax" id="rtax" class="form-control mySelect2">
                                             <option selected value="">Choose... </option>
                                             <option selected value="">Non PPN </option>
                                             <?php foreach ($getTax as $data) : ?>
                                                  <option <?= $invoice['rtax_id'] == $data['rtax_id'] ? 'selected' : ''; ?> value="<?= $data['rtax_id']; ?>"><?= $data['choice_name']; ?> (<?= $data['rtax_value']; ?>%)</option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="rtax_pph" class="form-label">Kategori Penagihan PPH</label>
                                        <select name="rtax_pph" id="rtax_pph" class="form-control mySelect2">
                                             <option selected value="">Pilih Billto Dulu</option>
                                             <option selected value="">Non PPH </option>
                                             <?php foreach ($getTaxPph as $data) : ?>
                                                  <option <?= $invoice['rtaxpph_id'] == $data['id'] ? 'selected' : ''; ?> value="<?= $data['id']; ?>"><?= $data['choice_name']; ?> (<?= $data['percent']; ?>%)</option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="notes" class="form-label">Catatan</label>
                                        <input type="text" class="form-control" id="notes" name="notes" value="<?= $invoice['notes']; ?>">
                                   </div>
                              </div>
                              <button type="submit" class="btn btn-primary">Simpan</button>
                         </form>

                    </div>
               </div>
          </div>
     </div>
</div>

<script>
     $(document).ready(function() {
          $('#pelanggan_id').on('change', function() {
               getTax();
               getTaxPph();
          })
     })

     function getTax() {
          var pelanggan_id = $('#pelanggan_id').val();
          $.ajax({
               url: "<?= site_url('data/invoice/getTax'); ?>",
               type: 'post',
               data: {
                    'pelanggan_id': pelanggan_id
               },
               dataType: 'json',
               success: function(response) {
                    var select = $('#rtax');
                    select.empty();

                    if (response.length > 0) {
                         select.append('<option value="">Pilih Kategori</option>');
                         select.append('<option value="">Non PPN</option>');
                         $.each(response, function(index, item) {
                              select.append('<option value="' + item.rtax_id + '">' + item.rtax_desc + ' (' + item.rtax_value + '%)</option>');
                         });
                    } else {
                         select.append('<option value="">Tidak ada data</option>');
                    }
               }
          });
     }

     function getTaxPph() {
          var pelanggan_id = $('#pelanggan_id').val();
          $.ajax({
               url: "<?= site_url('data/invoice/getTaxPph'); ?>",
               type: 'post',
               data: {
                    'pelanggan_id': pelanggan_id
               },
               dataType: 'json',
               success: function(response) {
                    var select = $('#rtax_pph');
                    select.empty();

                    if (response.length > 0) {
                         select.append('<option value="">Pilih Kategori</option>');
                         select.append('<option value="">Non PPN</option>');
                         $.each(response, function(index, item) {
                              select.append('<option value="' + item.id + '">' + item.choice_name + ' (' + item.percent + '%)</option>');
                         });
                    } else {
                         select.append('<option value="">Tidak ada data</option>');
                    }
               }
          });
     }
</script>


<?= $this->endSection(); ?>
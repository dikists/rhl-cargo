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
                         <a href="<?= base_url('admin/manifest/add'); ?>" class="btn btn-success btn-sm">
                              <i class="fas fa-plus"></i> Tambah data
                         </a>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <?php if (session()->getFlashdata('success')): ?>
                              <div class="alert alert-success">
                                   <?= session()->getFlashdata('success') ?>
                              </div>
                         <?php endif; ?>
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead>
                                        <tr>
                                             <th scope="col" class="text-center" width="3%">No</th>
                                             <th scope="col" class="text-center">Tanggal</th>
                                             <th scope="col" class="text-center">No Manifest</th>
                                             <th scope="col" class="text-center">Vendor</th>
                                             <th scope="col" class="text-center">No Surat Jalan</th>
                                             <th scope="col" class="text-center" width="5%">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody style="text-align: center; vertical-align: middle">
                                   </tbody>
                              </table>
                         </div>
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

<script>
     $(document).ready(function() {
          var tabel = $('#dt1').DataTable({
               processing: true, // Mengaktifkan tampilan "Processing..."
               language: {
                    processing: "Loading...", // Teks yang akan muncul saat loading
                    loadingRecords: "Loading data..."
               }
          });
          tampilkanData();

          function tampilkanData() {

               $.ajax({
                    url: "<?= base_url(); ?>data/getManifest",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.date).format('DD-MMMM-YY');
                              let rowData = [
                                   item.no,
                                   tanggalBaru,
                                   item.manifest_number,
                                   item.vendor_name,
                                   item.no_surat_jalan_list, // Pastikan sudah berisi HTML <ul>
                                   item.aksi
                              ];
                              let rowNode = tabel.row.add(rowData).draw(false).node();
                         });
                         tabel.order([0, 'asc']).draw();
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          }

          $(document).on('click', '.deleteVendor', function(e) {
               e.preventDefault();

               let id = $(this).data('id');
               let url = '/data/deleteVendor/' + id;

               // Konfirmasi penghapusan
               if (confirm('Apa kamu yakin ingin menghapus Vendor ini?')) {
                    $.ajax({
                         url: url,
                         type: 'POST',
                         data: {
                              _method: 'DELETE',
                              csrf_token_name: $('meta[name="csrf-token"]').attr('content')
                         },
                         success: function(response) {
                              alert(response['message']);
                              tampilkanData();
                         },
                         error: function(xhr) {
                              alert('Gagal menghapus Vendor!');
                         }
                    });
               }
          });

     });
</script>


<?= $this->endSection(); ?>
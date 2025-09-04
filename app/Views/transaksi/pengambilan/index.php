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
                         <a href="<?= base_url('admin/pengambilan/add'); ?>" class="btn btn-success btn-sm">
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
                                             <th scope="col" width="5%">No</th>
                                             <th scope="col" width="10%">Tanggal Pickup</th>
                                             <th scope="col" width="10%">No Resi</th>
                                             <th scope="col" width="10%">No Order</th>
                                             <th scope="col" width="15%">Pengirim</th>
                                             <th scope="col" width="15%">Penerima</th>
                                             <th scope="col" width="10%">Layanan</th>
                                             <th scope="col" width="10%">Driver</th>
                                             <th scope="col" class="text-center" width="15%">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
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
                    url: "<?= base_url(); ?>data/getPengambilan",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.tanggal_ambil).format('DD-MM-YYYY');
                              let rowData = [
                                   item.no,
                                   tanggalBaru,
                                   item.no_surat_jalan,
                                   item.no_order,
                                   item.nama_pelanggan,
                                   item.nama_penerima,
                                   item.layanan,
                                   item.driver_name.toUpperCase(),
                                   item.aksi,
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

          $(document).on('click', '.cetakLabel', function(e) {
               e.preventDefault();
               let id = $(this).data('id');
               let url = '/pdf/cetak_label/' + id;
               $('#idSJ').val(id);
          });

          $('#cetakLabelPdf').on('click', function() {
               var id = $('#idSJ').val();
               var qty = $('#qtyCetak').val();


               // document.location.href = '/pdf/cetak_label/' + id + "/" + qty;
               var myWindow = window.open('/pdf/cetak_label/' + id + "/" + qty, '_blank');

               setTimeout(() => {
                    $('#modalCetakLabel').modal('hide');
                    $('#qtyCetak').val('');

                    // myWindow.close();
                    // document.location.href = '';
               }, 3000);

          });

     });
</script>


<?= $this->endSection(); ?>
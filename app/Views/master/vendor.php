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
                         <button type="button" class="btn btn-primary btn-sm" id="addVendor" data-toggle="modal" data-target="#formVendor">
                              <i class="fas fa-plus"></i> Tambah Data
                         </button>
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
                                             <th scope="col">Nama</th>
                                             <th scope="col">Email</th>
                                             <th scope="col">Telepon</th>
                                             <th scope="col">Group ID</th>
                                             <th scope="col">Alamat</th>
                                             <th scope="col" class="text-center">Aksi</th>
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
<div class="modal fade" id="formVendor" tabindex="-1" aria-labelledby="formVendorLabel" aria-hidden="true">
     <div class="modal-dialog">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="formVendorLabel">Tambah Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <form>
                         <div class="mb-3">
                              <label for="vendor_name" class="form-label">Nama Vendor*</label>
                              <input type="text" class="form-control" id="vendor_name" name="vendor_name" required>
                         </div>
                         <div class="mb-3">
                              <label for="short_name" class="form-label">Alias*</label>
                              <input type="text" class="form-control" id="short_name" name="short_name" required>
                         </div>

                         <div class="mb-3">
                              <label for="vendor_email" class="form-label">Email Vendor*</label>
                              <input type="email" class="form-control" id="vendor_email" name="vendor_email" required>
                         </div>

                         <div class="mb-3">
                              <label for="vendor_phone" class="form-label">Nomor Telepon Vendor*</label>
                              <input type="text" class="form-control" id="vendor_phone" name="vendor_phone" required>
                         </div>

                         <div class="mb-3">
                              <label for="vendor_group_phone" class="form-label">Group Vendor ID*</label>
                              <input type="text" class="form-control" id="vendor_group_phone" name="vendor_group_phone" required>
                         </div>

                         <div class="mb-3">
                              <label for="vendor_address" class="form-label">Alamat Vendor*</label>
                              <textarea class="form-control" id="vendor_address" name="vendor_address" rows="3" required></textarea>
                         </div>
                         <input type="hidden" class="form-control" id="id_vendor" name="id_vendor">
                    </form>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSave">Save</button>
               </div>
          </div>
     </div>
</div>

<script>
     $(document).ready(function() {
          var tabel = $('#dt1').DataTable();
          tampilkanData();

          function tampilkanData() {

               $.ajax({
                    url: "<?= base_url(); ?>data/getVendor",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let rowData = [
                                   item.vendor_name,
                                   item.vendor_email,
                                   item.vendor_phone,
                                   item.vendor_group_phone,
                                   item.vendor_address,
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

          $('#addVendor').on('click', function() {
               clear_form();
               $('#formVendor').modal('show');
               $('#formVendorLabel').text('Tambah Vendor');
               $('#btnSave').text('Save');
          })
          $('#btnSave').on('click', function() {
               let vendor_id = $('#id_vendor').val();
               let vendor_name = $('#vendor_name').val();
               let short_name = $('#short_name').val();
               let vendor_email = $('#vendor_email').val();
               let vendor_phone = $('#vendor_phone').val();
               let vendor_group_phone = $('#vendor_group_phone').val();
               let vendor_address = $('#vendor_address').val();

               if (!vendor_name || !vendor_email || !vendor_phone || !vendor_address) {
                    alert('isi semua data');
                    return false;
               }

               var url = '<?= base_url(); ?>data/saveVendor';
               if ($('#btnSave').text() === 'Update') {
                    var url = '<?= base_url(); ?>data/updateVendor';
               }

               $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                         vendor_id: vendor_id,
                         vendor_name: vendor_name,
                         short_name: short_name,
                         vendor_email: vendor_email,
                         vendor_phone: vendor_phone,
                         vendor_group_phone: vendor_group_phone,
                         vendor_address: vendor_address,
                         _method: 'POST',
                         csrf_token_name: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                         alert(response['message']);
                         tampilkanData();
                         $('#formVendor').modal('hide');
                         clear_form();
                    },
                    error: function(xhr) {
                         alert('Gagal menambahkan Vendor!');
                    }
               });
          });

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

          $(document).on('click', '.editVendor', function(e) {
          console.log($(this).data());
               let id = $(this).data('id');
               let name = $(this).data('name');
               let email = $(this).data('email');
               let phone = $(this).data('phone');
               let vendor_group_phone = $(this).data('group_phone');
               let address = $(this).data('address');
               $('#formVendorLabel').text('Edit Vendor');
               $('#btnSave').text('Update');
               $('#formVendor').modal('show');
               $('#id_vendor').val(id);
               $('#vendor_name').val(name);
               $('#vendor_email').val(email);
               $('#vendor_phone').val(phone);
               $('#vendor_group_phone').val(vendor_group_phone);
               $('#vendor_address').val(address);
          });

     });

     function clear_form() {
          $('#vendor_name').val('');
          $('#vendor_email').val('');
          $('#vendor_phone').val('');
          $('#vendor_group_phone').val('');
          $('#vendor_address').val('');
     }
</script>


<?= $this->endSection(); ?>
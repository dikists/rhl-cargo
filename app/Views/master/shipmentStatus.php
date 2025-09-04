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
                         <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="addStatus" data-target="#formStatus">
                              <i class="fas fa-plus"></i> Tambah Data
                         </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="formStatus" aria-labelledby="formStatusLabel" aria-hidden="true">
                         <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                   <div class="modal-header">
                                        <h5 class="modal-title" id="formStatusLabel">Tambah Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                        </button>
                                   </div>
                                   <div class="modal-body">
                                        <form>
                                             <div class="form-row">
                                                  <div class="form-group col-md-12">
                                                       <label for="status_name">Nama Status</label>
                                                       <input type="text" name="status_name" class="form-control" id="status_name" placeholder="Masukkan Nama Status">
                                                  </div>
                                             </div>
                                             <input type="hidden" id="id" name="id">
                                        </form>
                                   </div>
                                   <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead>
                                        <tr>
                                             <th scope="col">Nama Pemilik Status</th>
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

<script>
     $(document).ready(function() {
          var tabel = $('#dt1').DataTable();
          tampilkanData();

          function tampilkanData() {

               $.ajax({
                    url: "<?= base_url(); ?>data/getshipmentStatus",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let rowData = [
                                   item.status_name,
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

          $('#addStatus').on('click', function() {
               clear_form();
               $('#formStatusLabel').text('Tambah Status Pengiriman');
               $('#saveButton').text('Add');
          })

          $('#saveButton').on('click', function() {
               var cekTombol = $('#saveButton').text();
               var url = "";

               if (cekTombol === 'Add') {
                    url = "<?= base_url(); ?>data/saveStatus";
               } else if (cekTombol === 'Update') {
                    url = "<?= base_url(); ?>data/updateStatus";
               }

               let status_name = $('#status_name').val();
               let id = $('#id').val();

               // Validasi input
               if (status_name === '' ) {
                    alert('Data tidak boleh ada yang kosong');
                    return;
               }

               // Ajax untuk menyimpan atau memperbarui data
               $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                         id: id,
                         status_name: status_name,
                    },
                    dataType: 'json',
                    success: function(response) {
                         alert(response['message']);
                         tampilkanData();
                         $('#formStatus').modal('hide');
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          });


          $(document).on('click', '.editStatus', function(e) {
               let id = $(this).data('id');
               let status_name = $(this).data('status_name');

               $('#formStatusLabel').text('Edit Status');
               $('#saveButton').text('Update');
               $('#formStatus').modal('show');

               $('#id').val(id);
               $('#status_name').val(status_name);
          });

          $(document).on('click', '.deleteStatus', function(e) {
               e.preventDefault();

               let id = $(this).data('id');
               let url = '/data/deleteStatus/' + id;

               // Konfirmasi penghapusan
               if (confirm('Apa kamu yakin ingin menghapus Status ini?')) {
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
                              alert('Gagal menghapus Status!');
                         }
                    });
               }
          });
     });

     function clear_form() {
          $('#status_name').val('');
          $('#id').val('');
     }
</script>


<?= $this->endSection(); ?>
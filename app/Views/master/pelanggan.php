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
                         <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="addRekening" data-target="#formPelanggan">
                              <i class="fas fa-plus"></i> Tambah Data
                         </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="formPelanggan" aria-labelledby="formPelangganLabel" aria-hidden="true">
                         <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                   <div class="modal-header">
                                        <h5 class="modal-title" id="formPelangganLabel">Tambah Data Pelanggan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                        </button>
                                   </div>
                                   <div class="modal-body">
                                        <form>
                                             <div class="form-row">
                                                  <div class="form-group col-md-4">
                                                       <label for="nama">Nama</label>
                                                       <input type="text" class="form-control" name="nama" id="nama">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="email">Email</label>
                                                       <input type="email" class="form-control" name="email" id="email">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="telp">Telepon</label>
                                                       <input type="text" class="form-control" name="telp" id="telp">
                                                  </div>
                                             </div>
                                             <div class="form-row">
                                                  <div class="form-group col-md-8">
                                                       <label for="alamat">Alamat</label>
                                                       <input type="text" class="form-control" name="alamat" id="alamat" placeholder="1234 Main St">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="kota">Kota</label>
                                                       <input type="text" class="form-control" name="kota" id="kota">
                                                  </div>
                                             </div>
                                             <div class="form-row">
                                                  <div class="form-group col-md-4">
                                                       <label for="top">Top</label>
                                                       <input type="text" class="form-control" name="top" id="top">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="npwp">NPWP</label>
                                                       <input type="text" class="form-control" name="npwp" id="npwp">
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
                                             <th scope="col">Nama</th>
                                             <th scope="col" width="30%">Alamat</th>
                                             <th scope="col">Kota</th>
                                             <th scope="col">Telp</th>
                                             <th scope="col">Email</th>
                                             <th scope="col">TOP</th>
                                             <th scope="col">NPWP</th>
                                             <th scope="col">Master</th>
                                             <th scope="col">Aksi</th>
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
                    url: "<?= base_url(); ?>data/getPelanggan",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let rowData = [
                                   item.nama_pelanggan,
                                   item.alamat_pelanggan,
                                   item.kota,
                                   item.telepon_pelanggan,
                                   item.email,
                                   item.top,
                                   item.npwp,
                                   item.master,
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

          $('#addRekening').on('click', function() {
               clear_form();
               $('#formPelangganLabel').text('Tambah Pelanggan');
               $('#saveButton').text('Add');
          })

          $('#saveButton').on('click', function() {
               var cekTombol = $('#saveButton').text();
               var url = "";

               if (cekTombol === 'Add') {
                    url = "<?= base_url(); ?>data/savePelanggan";
               } else if (cekTombol === 'Update') {
                    url = "<?= base_url(); ?>data/updatePelanggan";
               }

               let nama = $('#nama').val();
               let email = $('#email').val();
               let top = $('#top').val();
               let npwp = $('#npwp').val();
               let telp = $('#telp').val();
               let alamat = $('#alamat').val();
               let kota = $('#kota').val();
               let id = $('#id').val();

               // Validasi input
               if (nama === '' || email === '' || telp === '' || alamat === '') {
                    alert('Data tidak boleh ada yang kosong');
                    return;
               }

               // Ajax untuk menyimpan atau memperbarui data
               $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                         id: id,
                         nama: nama,
                         email: email,
                         top: top,
                         npwp: npwp,
                         telp: telp,
                         kota: kota,
                         alamat: alamat
                    },
                    dataType: 'json',
                    success: function(response) {
                         alert(response['message']);
                         tampilkanData();
                         $('#formPelanggan').modal('hide');
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          });


          $(document).on('click', '.editPelanggan', function(e) {
               let id = $(this).data('id');
               let nama = $(this).data('nama_pelanggan');
               let alamat = $(this).data('alamat_pelanggan');
               let kota = $(this).data('kota');
               let telp = $(this).data('telepon_pelanggan');
               let email = $(this).data('email');
               let top = $(this).data('top');
               let npwp = $(this).data('npwp');

               $('#formPelangganLabel').text('Edit Pelanggan');
               $('#saveButton').text('Update');
               $('#formPelanggan').modal('show');

               $('#id').val(id);
               $('#nama').val(nama);
               $('#email').val(email);
               $('#top').val(top);
               $('#telp').val(telp);
               $('#alamat').val(alamat);
               $('#kota').val(kota);
               $('#npwp').val(npwp);
          });

          $(document).on('click', '.deletePelanggan', function(e) {
               e.preventDefault();

               let id = $(this).data('id');
               let url = '/data/deletePelanggan/' + id;

               // Konfirmasi penghapusan
               if (confirm('Apa kamu yakin ingin menghapus Pelanggan ini?')) {
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
                              alert('Gagal menghapus Pelanggan!');
                         }
                    });
               }
          });
     });

     function clear_form() {
          $('#nama').val('');
          $('#email').val('');
          $('#top').val('');
          $('#telp').val('');
          $('#alamat').val('');
          $('#kota').val('');
          $('#id').val('');
     }
</script>


<?= $this->endSection(); ?>
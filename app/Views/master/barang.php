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
                         <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="addBarang" data-target="#formBarang">
                              <i class="fas fa-plus"></i> Tambah Data
                         </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="formBarang" aria-labelledby="formBarangLabel" aria-hidden="true">
                         <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                   <div class="modal-header">
                                        <h5 class="modal-title" id="formBarangLabel">Tambah Barang</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                        </button>
                                   </div>
                                   <div class="modal-body">
                                        <form>
                                             <div class="form-row">
                                                  <div class="form-group col-md-12">
                                                       <label for="nama_barang">Nama Barang</label>
                                                       <input type="text" class="form-control" id="nama_barang" placeholder="Nama Barang">
                                                  </div>
                                             </div>
                                             <div class="form-row">
                                                  <div class="form-group col-md-12">
                                                       <label for="satuan">Satuan</label>
                                                       <select name="satuan" id="satuan" class="form-control mySelect2">
                                                            <option value="">Pilih Satuan</option>
                                                            <?php foreach ($satuan as $key => $value) : ?>
                                                                 <option value="<?= $value['choice_name']; ?>"><?= ucwords($value['choice_name']); ?></option>
                                                            <?php endforeach; ?>
                                                       </select>
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
                                             <th scope="col">Nama Barang</th>
                                             <th scope="col">Satuan</th>
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
                    url: "<?= base_url(); ?>data/getBarang",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let rowData = [
                                   item.nama_barang,
                                   item.satuan,
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

          $('#addBarang').on('click', function() {
               clear_form();
               $('#formBarangLabel').text('Tambah Barang');
               $('#saveButton').text('Add');
          })

          $('#saveButton').on('click', function() {
               var cekTombol = $('#saveButton').text();
               if (cekTombol === 'Add') {
                    var url = "<?= base_url(); ?>data/saveBarang";
               } else if (cekTombol === 'Update') {
                    var url = "<?= base_url(); ?>data/updateBarang";
               }

               let id = $('#id').val();
               let nama_barang = $('#nama_barang').val();
               let satuan = $('#satuan').val();

               $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                         id: id,
                         nama_barang: nama_barang,
                         satuan: satuan
                    },
                    dataType: 'json',
                    success: function(response) {
                         alert(response['message']);
                         tampilkanData();
                         $('#formBarang').modal('hide');
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          });

          $(document).on('click', '.editBarang', function(e) {
               let id = $(this).data('id');
               let name = $(this).data('name');
               let satuan = $(this).data('satuan');
               $('#formBarangLabel').text('Edit Barang');
               $('#saveButton').text('Update');
               $('#formBarang').modal('show');
               $('#id').val(id);
               $('#nama_barang').val(name);
               $('#satuan').val(satuan).trigger('change');
          });

          $(document).on('click', '.deleteBarang', function(e) {
               e.preventDefault();

               let id = $(this).data('id');
               let url = '/data/deleteBarang/' + id;

               // Konfirmasi penghapusan
               if (confirm('Apa kamu yakin ingin menghapus barang ini?')) {
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
                              alert('Gagal menghapus barang!');
                         }
                    });
               }
          });
     });

     function clear_form() {
          $('#nama_barang').val('');
          $('#satuan').val('').trigger('change');
          $('#id').val('');
     }
</script>


<?= $this->endSection(); ?>
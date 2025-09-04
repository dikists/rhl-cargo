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
                         <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="addRekening" data-target="#formRekening">
                              <i class="fas fa-plus"></i> Tambah Data
                         </button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="formRekening" aria-labelledby="formRekeningLabel" aria-hidden="true">
                         <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                   <div class="modal-header">
                                        <h5 class="modal-title" id="formRekeningLabel">Tambah Barang</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                        </button>
                                   </div>
                                   <div class="modal-body">
                                        <form>
                                             <div class="form-row">
                                                  <div class="form-group col-md-4">
                                                       <label for="holder_name">Nama Pemilik Rekening</label>
                                                       <input type="text" name="holder_name" class="form-control" id="holder_name" placeholder="Masukkan Nama Pemilik Rekening">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="account_number">Nomor Rekening</label>
                                                       <input type="text" name="account_number" class="form-control" id="account_number" placeholder="Masukkan Nomor Rekening">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="bank_name">Nama Bank</label>
                                                       <input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="Masukkan Nama Bank">
                                                  </div>
                                             </div>
                                             <div class="form-row">
                                                  <div class="form-group col-md-4">
                                                       <label for="account_type">Tipe Rekening</label>
                                                       <select id="account_type" name="account_type" class="form-control mySelect2">
                                                            <option value="">Choose...</option>
                                                            <option value="Tabungan">Tabungan</option>
                                                            <option value="Giro">Giro</option>
                                                            <option value="Bisnis">Bisnis</option>
                                                       </select>
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="signatory">Penanda Tangan</label>
                                                       <input type="text" name="signatory" class="form-control" id="signatory" placeholder="Masukkan Penanda Tangan">
                                                  </div>
                                                  <div class="form-group col-md-4">
                                                       <label for="balance">Saldo</label>
                                                       <input type="number" name="balance" class="form-control" id="balance" placeholder="Masukkan Saldo">
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
                                             <th scope="col">Nama Pemilik Rekening</th>
                                             <th scope="col">Nomor Rekening</th>
                                             <th scope="col">Signatory</th>
                                             <th scope="col">Bank</th>
                                             <th scope="col">Tipe</th>
                                             <th scope="col">Saldo</th>
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
                    url: "<?= base_url(); ?>data/getRekening",
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let rowData = [
                                   item.account_holder_name,
                                   item.account_number,
                                   item.signatory,
                                   item.bank_name,
                                   item.account_type,
                                   item.balance,
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
               $('#formRekeningLabel').text('Tambah Barang');
               $('#saveButton').text('Add');
          })

          $('#saveButton').on('click', function() {
               var cekTombol = $('#saveButton').text();
               var url = "";

               if (cekTombol === 'Add') {
                    url = "<?= base_url(); ?>data/saveRekening";
               } else if (cekTombol === 'Update') {
                    url = "<?= base_url(); ?>data/updateRekening";
               }

               let holder_name = $('#holder_name').val();
               let account_number = $('#account_number').val();
               let bank_name = $('#bank_name').val();
               let account_type = $('#account_type').val();
               let id = $('#id').val();
               let signatory = $('#signatory').val();
               let balance = $('#balance').val();

               // Validasi input
               if (holder_name === '' || account_number === '' || bank_name === '' || account_type === '') {
                    alert('Data tidak boleh ada yang kosong');
                    return;
               }

               // Ajax untuk menyimpan atau memperbarui data
               $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                         id: id,
                         holder_name: holder_name,
                         account_number: account_number,
                         bank_name: bank_name,
                         account_type: account_type,
                         signatory: signatory,
                         balance: balance
                    },
                    dataType: 'json',
                    success: function(response) {
                         alert(response['message']);
                         tampilkanData();
                         $('#formRekening').modal('hide');
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          });


          $(document).on('click', '.editRekening', function(e) {
               let id = $(this).data('id');
               let holder_name = $(this).data('holder_name');
               let account_number = $(this).data('account_number');
               let bank_name = $(this).data('bank_name');
               let account_type = $(this).data('account_type');
               let balance = $(this).data('balance');
               let signatory = $(this).data('signatory');

               $('#formRekeningLabel').text('Edit Rekening');
               $('#saveButton').text('Update');
               $('#formRekening').modal('show');

               $('#id').val(id);
               $('#holder_name').val(holder_name);
               $('#account_number').val(account_number);
               $('#bank_name').val(bank_name);
               $('#account_type').change().val(account_type).trigger('change');
               $('#balance').val(balance);
               $('#signatory').val(signatory);
          });

          $(document).on('click', '.deleteRekening', function(e) {
               e.preventDefault();

               let id = $(this).data('id');
               let url = '/data/deleteRekening/' + id;

               // Konfirmasi penghapusan
               if (confirm('Apa kamu yakin ingin menghapus Rekening ini?')) {
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
                              alert('Gagal menghapus Rekening!');
                         }
                    });
               }
          });
     });

     function clear_form() {
          $('#holder_name').val('');
          $('#account_number').val('');
          $('#bank_name').val('');
          $('#account_type').val('').trigger('change');
          $('#balance').val('');
          $('#signatory').val('');
          $('#id').val('');
     }
</script>


<?= $this->endSection(); ?>
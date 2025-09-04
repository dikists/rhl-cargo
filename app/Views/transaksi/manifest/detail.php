<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <div class="align-items-center mb-4">
          <h1 class="h3 mb-0 text-gray-800 "><?= $title; ?> <?= $head[0]['manifest_number']; ?></h1>
     </div>

     <div class="row mb-3">

          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">
               <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                         <?php foreach (session()->getFlashdata('errors') as $error): ?>
                              <p><?= $error ?></p>
                         <?php endforeach; ?>
                    </div>
               <?php endif; ?>
               <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                         <?= session()->getFlashdata('success') ?>
                    </div>
               <?php endif; ?>
               <div class="card mb-5">
                    <div class="card-header border border-primary">
                         <h4> Vendor : <?= $head[0]['vendor_name']; ?></h4>
                    </div>
                    <div class="card-body border border-primary">

                         <a href="<?= base_url('admin/manifest'); ?>" class="btn btn-sm btn-warning mb-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                         <button type="button" class="btn btn-sm btn-primary mb-2" data-toggle="modal" data-target="#tambahSJ">
                              Tambah
                         </button>

                         <!-- Modal -->
                         <div class="modal fade" id="tambahSJ" data-keyboard="false" tabindex="-1" aria-labelledby="tambahSJLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                   <div class="modal-content">
                                        <div class="modal-header">
                                             <h5 class="modal-title" id="tambahSJLabel">Tambah Surat Jalan </h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                             </button>
                                        </div>
                                        <div class="modal-body">
                                             <?= csrf_field(); ?>

                                             <div class="form-row">
                                                  <div class="form-group col-md-6">
                                                       <label for="surat_jalan">Surat Jalan*</label>
                                                       <select id="surat_jalan" name="surat_jalan" class="form-control mySelect2" required>
                                                            <option selected value="">Choose...</option>
                                                       </select>
                                                  </div>
                                                  <div class="form-group col-md-6">
                                                       <label for="sub_vendor">Sub Vendor</label>
                                                       <select id="sub_vendor" name="sub_vendor" class="form-control mySelect2">
                                                            <option selected value="">Choose...</option>
                                                            <?php foreach ($vendors as $data) : ?>
                                                                 <option value="<?= $data['vendor_id']; ?>"><?= $data['vendor_name']; ?></option>
                                                            <?php endforeach; ?>
                                                       </select>
                                                  </div>
                                             </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                             <button type="button" name="tambah" id="tambah-manifest-surat-jalan" class="btn btn-primary">Tambah</button>
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead>
                                        <tr>
                                             <th scope="col">#</th>
                                             <th scope="col">No Surat Jalan</th>
                                             <th scope="col">Shipper</th>
                                             <th scope="col">Consignee</th>
                                             <th scope="col">Sub Vendor</th>
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
          let id_order = $('#id_order').val();
          var tabel = $('#dt1').DataTable();
          tampilkanData();

          function tampilkanData() {

               $.ajax({
                    url: "<?= base_url(); ?>data/getDetailManifest/" + <?= $head[0]['manifest_id']; ?>,
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');
                              let rowData = [
                                   index + 1,
                                   item.no_surat_jalan,
                                   item.pengirim,
                                   item.penerima,
                                   item.vendor_name,
                                   `<button class="btn btn-danger btn-sm btn-hapus m-1" data-id="` + item.id_detail_manifest + `"><i class="fas fa-trash m-1"></i></button>`
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

          function bersihkanForm() {
               $('#sub_vendor').val(null).trigger('change');
          }

          $('#tambah-manifest-surat-jalan').click(function(event) {
               event.preventDefault();
               let surat_jalan = $('#surat_jalan').val();
               let sub_vendor = $('#sub_vendor').val();

               if (surat_jalan == "") {
                    alert('Data Belum Lengkap');
                    return false;
               }

               $.ajax({
                    url: "<?= base_url(); ?>data/add_detail_manifest",
                    method: "POST",
                    data: {
                         date: '<?= $head[0]['date']; ?>',
                         manifest_id: '<?= $head[0]['manifest_id']; ?>',
                         surat_jalan: surat_jalan,
                         sub_vendor: sub_vendor,
                    },
                    success: function(response) {
                         console.log(response);
                         if (response == "ok") {
                              tampilkanData();
                              bersihkanForm();
                              $('#tambahSJ').modal('hide');

                         }
                    },
                    error: function(xhr, status, error) {
                         console.error(xhr.responseText);
                         alert('Terjadi kesalahan saat menambahkan barang!');
                    }
               });
          })

          $('#dt1').on('click', '.btn-hapus', function() {
               let id_detail = $(this).data('id');
               if (confirm("Apakah Anda yakin ingin menghapus barang ini?")) {
                    $.ajax({
                         url: "<?= base_url(); ?>data/delete_detail_manifest",
                         method: "POST",
                         data: {
                              id: id_detail
                         },
                         success: function(response) {
                              tampilkanData();
                         }
                    });
               }
          });

          $('#tambahSJ').on('show.bs.modal', function() {
               $.ajax({
                    url: '/data/getSJManifest',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                         // Hapus opsi lama
                         $('#surat_jalan').empty().append('<option selected value="">Choose...</option>');

                         // Tambahkan opsi baru ke dropdown
                         response.forEach(function(item) {
                              $('#surat_jalan').append('<option value="' + item.id_surat_jalan + '">' + item.no_surat_jalan + ' - ' + item.pengirim + ' - ' + item.penerima + '</option>');
                         });
                    },
                    error: function(xhr, status, error) {
                         console.error('Terjadi kesalahan:', error);
                    }
               });
          });
     });
</script>

<?= $this->endSection(); ?>
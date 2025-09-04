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
                         <?php
                         $role = session()->get('role');
                         if ($role !== 'PIC RELASI') {
                         ?>
                              <a href="<?= base_url('admin/surat_jalan/add'); ?>" class="btn btn-success btn-sm">
                                   <i class="fas fa-plus"></i> Tambah data
                              </a>
                         <?php
                         }
                         ?>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <?php if (session()->getFlashdata('success')): ?>
                              <div class="alert alert-success">
                                   <?= session()->getFlashdata('success') ?>
                              </div>
                         <?php endif; ?>
                         <form class="mb-2">
                              <div class="row my-2">
                                   <div class="col-sm-3">
                                        <label for="date_start">Tanggal Awal</label>
                                        <input type="text" class="form-control datepicker_id" id="date_start" name="date_start" value="<?= date('01-m-Y'); ?>">
                                   </div>
                                   <div class="col-sm-3">
                                        <label for="date_end">Tanggal Akhir</label>
                                        <input type="text" class="form-control datepicker_id" id="date_end" name="date_end" value="<?= date('d-m-Y'); ?>">
                                   </div>
                                   <div class="col-sm-3">
                                        <label for="shipper">Pengirim</label>
                                        <select id="pengirim" name="pengirim" class="form-control mySelect2" required>
                                             <option selected value="">Semua</option>
                                             <?php foreach ($pengirim as $data) : ?>
                                                  <option value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="col-sm-3">
                                        <label for="penerima">Penerima</label>
                                        <select name="penerima" id="penerima" class="form-control mySelect2">
                                             <option value="">Semua</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="row my-2">
                                   <div class="col-sm-3">
                                        <label for="layanan">Layanan</label>
                                        <select name="layanan" id="layanan" class="form-control mySelect2">
                                             <option value="">Semua</option>
                                             <?php foreach ($layanan as $data) : ?>
                                                  <option value="<?= $data['id']; ?>"><?= $data['choice_name']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>
                              <center>
                                   <button type="button" class="btn btn-primary my-2 btn-sm" id="filterButton"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</button>
                              </center>
                         </form>
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead>
                                        <tr>
                                             <th scope="col" width="3%">No</th>
                                             <th scope="col" width="8">Tanggal Buat</th>
                                             <th scope="col" width="8%">No Resi</th>
                                             <th scope="col" width="10%">No Order</th>
                                             <th scope="col" width="10%">No ref</th>
                                             <th scope="col" width="15%">Pengirim</th>
                                             <th scope="col" width="15%">Penerima</th>
                                             <th scope="col" width="10%">Driver</th>
                                             <th scope="col" width="10%">Layanan</th>
                                             <th scope="col" class="text-center" width="20%">Aksi</th>
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
                    <h5 class="modal-title" id="modalCetakLabelLabel">Modal Cetak Label</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <form>
                         <div class="form-group">
                              <label for="qtyCetak">Qty</label>
                              <input type="text" class="form-control" id="qtyCetak" autocomplete="off">
                         </div>
                         <div class="form-group">
                              <label for="beratCetak">Berat</label>
                              <input type="text" class="form-control" id="beratCetak" autocomplete="off">
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
               let date_start = convertDate($('#date_start').val());
               let date_end = convertDate($('#date_end').val());
               let pengirim = $('#pengirim').val();
               let penerima = $('#penerima').val();
               let layanan = $('#layanan').val();

               $.ajax({
                    url: "<?= base_url(); ?>data/getSuratJalan",
                    method: "GET",
                    data: {
                         date_start: date_start,
                         date_end: date_end,
                         pengirim: pengirim,
                         penerima: penerima,
                         layanan: layanan
                    },
                    dataType: 'json',
                    beforeSend: function() {
                         $('#dt1').addClass('loading');
                    },
                    complete: function() {
                         $('#dt1').removeClass('loading');
                    },
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');
                              let rowData = [
                                   item.no,
                                   convertDate(item.tgl_pembuatan, 'YYYY-MM-DD', 'DD-MM-YYYY'),
                                   item.no_surat_jalan,
                                   item.no_order,
                                   item.no_ref,
                                   item.nama_pelanggan,
                                   item.nama_penerima,
                                   item.kurir + ' - ' + item.plate_number,
                                   item.layanan,
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
               var berat = $('#beratCetak').val();


               // Buat query string
               var param = '?id=' + encodeURIComponent(id) +
                    '&qty=' + encodeURIComponent(qty) +
                    '&berat=' + encodeURIComponent(berat);

               // Buka halaman baru dengan URL yang menggunakan query string
               var myWindow = window.open('/pdf/cetak_label/' + param, '_blank');

               setTimeout(() => {
                    $('#modalCetakLabel').modal('hide');
                    $('#qtyCetak').val('');

                    // myWindow.close();
                    // document.location.href = '';
               }, 3000);

          });

          // filter pengirim
          $('#pengirim').on('change', function() {
               var id = this.value;
               $.ajax({
                    url: "<?= base_url('data/get_penerima_by_pengirim/'); ?>" + id,
                    method: "GET",
                    dataType: 'json',
                    success: function(data) {
                         var penerimaSelect = $('#penerima');
                         penerimaSelect.empty(); // Kosongkan dulu opsi sebelumnya
                         penerimaSelect.append('<option selected value="">Semua</option>'); // Tambahkan opsi default

                         // Looping data hasil AJAX dan tambahkan ke dalam select
                         $.each(data, function(index, item) {
                              penerimaSelect.append(`<option value="` + item.id_penerima + `" 
                              data-id-prov="` + item.provinsi_id + `"
                              data-prov="` + item.provinsi + `" 
                              data-id-kab="` + item.kabupaten_id + `"
                              data-kab="` + item.kabupaten + `" 
                              data-id-kec="` + item.kecamatan_id + `"
                              data-kec="` + item.kecamatan + `"
                              >` + item.nama_penerima + `</option>`);
                         });
                    },
                    error: function(xhr, status, error) {
                         console.error("Error fetching data:", error);
                    }
               });
          });

          $('#filterButton').on('click', function() {
               tampilkanData();
          });

     });
</script>


<?= $this->endSection(); ?>
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
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
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
                                   <div class="col-sm-3">
                                        <label for="performance">Performance</label>
                                        <select name="performance" id="performance" class="form-control mySelect2">
                                             <option value="" selected>Semua</option>
                                             <option value="On Process">On Process</option>
                                             <option value="Ontime">Ontime</option>
                                             <option value="Not Ontime">Not Ontime</option>
                                        </select>
                                   </div>
                              </div>
                              <center>
                                   <button type="button" class="btn btn-primary my-2 btn-sm" id="filterButton"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</button>
                              </center>
                         </form>
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead style="font-size: 13px;">
                                        <tr>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="2%">No</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Tanggal Order</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Tanggal Kirim</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">No Resi</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="15%">Pengirim</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="15%">Penerima</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="2%">SLA</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="15%">Driver</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Layanan</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Vendor</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Sub Vendor</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Jumlah</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Berat</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">LT Actual</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Performa</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Cost</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Asuransi</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Surcharge</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Packing</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Arival Date</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Status</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">DTO</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Remark</th>
                                        </tr>
                                   </thead>
                                   <tbody style="font-size: 11px; text-align: center; font-weight: 700" class="text-dark">
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
          var tabel = $('#dt1').DataTable({
               dom: 'Bfrtip',
               buttons: [
                    'copy', 'csv', 'excel'
               ],
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
               let performance = $('#performance').val();

               $.ajax({
                    url: "<?= base_url(); ?>data/getPengiriman",
                    method: "GET",
                    data: {
                         date_start: date_start,
                         date_end: date_end,
                         pengirim: pengirim,
                         penerima: penerima,
                         layanan: layanan,
                         performance: performance
                    },
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalOrder = moment(item.tanggal_order).format('DD-MMMM-YY');
                              let tanggalKirim = moment(item.tanggal_kirim).format('DD-MMMM-YY');
                              let arrivalDate = item.tanggal_terima ? moment(item.tanggal_terima).format('DD-MMMM-YY') : '-';
                              let rowData = [
                                   item.no,
                                   tanggalOrder,
                                   tanggalKirim,
                                   item.surat_jalan,
                                   item.nama_pelanggan,
                                   item.nama_penerima,
                                   item.leadtime,
                                   item.plate_number,
                                   item.layanan,
                                   item.vendor_name,
                                   item.sub_vendor_name,
                                   item.koli + ' Koli',
                                   item.berat + ' KG',
                                   item.lt_actual,
                                   item.performance,
                                   formatRupiah(item.biaya_paket),
                                   formatRupiah(item.insurance),
                                   formatRupiah(item.surcharge),
                                   formatRupiah(item.biaya_packing),
                                   arrivalDate,
                                   item.status,
                                   item.dto,
                                   item.remark
                              ];
                              let rowNode = tabel.row.add(rowData).draw(false).node();
                              if (item.performance === 'Not Ontime') {
                                   $(rowNode).addClass('text-dark');
                                   $(rowNode).addClass('bg-pending');
                              } else if (item.performance === 'Ontime') {
                                   $(rowNode).addClass('bg-ontime');
                              }

                         });
                         tabel.order([0, 'asc']).draw();
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          }

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
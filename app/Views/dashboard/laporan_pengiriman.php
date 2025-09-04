<?= $this->extend('layout/template'); ?>
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
                                        <input type="text" class="form-control" id="pengirim_name" name="pengirim_name" value="<?= $pengirim['nama_pelanggan']; ?>" readonly="readonly">
                                        <input type="text" class="form-control" id="pengirim" name="pengirim" value="<?= $pengirim['id_pelanggan']; ?>" hidden="hidden">
                                   </div>
                                   <div class="col-sm-3">
                                        <label for="penerima">Penerima</label>
                                        <select name="penerima" id="penerima" class="form-control mySelect2">
                                             <option value="" selected>Semua</option>
                                             <?php foreach ($penerima as $p) : ?>
                                                  <option value="<?= $p['id_penerima']; ?>"><?= $p['nama_penerima']; ?></option>
                                             <?php endforeach; ?>
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
                                   <button type="button" class="btn btn-danger my-2 btn-sm" id="pdfButton"><i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF</button>
                              </center>
                         </form>
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead style="font-size: 13px;">
                                        <tr>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Resi Number</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Reference No.</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Pickup Date</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Origin</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="15%">Dest</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="2%">SLA</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="15%">Truck Number</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Service</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Qty</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="5%">Satuan</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Weight</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Volume</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Arival Date</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Receipt Name</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">LT Actual</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Performance</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Cost/Kg</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Add Cost</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Asuransi</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Surcharge</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Total Cost</th>
                                             <th scope="col" class="text-center" style="font-size: 11px" width="10%">Month</th>
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
                         performance: performance,
                         type: 'report'
                    },
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalOrder = moment(item.tanggal_order).format('DD-MMMM-YY');
                              let month = moment(item.tanggal_order).format('MM');
                              let tanggalKirim = moment(item.tanggal_kirim).format('DD-MMMM-YY');
                              let arrivalDate = item.tanggal_terima ? moment(item.tanggal_terima).format('DD-MMMM-YY') : '-';
                              let biaya_paket = cleanNumber(item.biaya_paket);
                              let biaya_packing = cleanNumber(item.biaya_packing);
                              let insurance = cleanNumber(item.insurance);
                              let surcharge = cleanNumber(item.surcharge);
                              let berat = cleanNumber(item.berat);
                              let totalCost = formatRupiah((biaya_paket * berat) + biaya_packing + insurance + surcharge);

                              if (item.bill_type === 'flat') {
                                   totalCost = formatRupiah(biaya_paket);
                              }
                              // let totalCost = formatRupiah(
                              //     (cleanNumber(item.biaya_paket) * ceil(cleanNumber(item.berat))) +
                              //     cleanNumber(item.biaya_packing) +
                              //     cleanNumber(item.insurance) +
                              //     cleanNumber(item.surcharge)
                              // );

                              let rowData = [
                                   item.surat_jalan,
                                   item.no_ref,
                                   tanggalOrder,
                                   item.origin,
                                   item.nama_penerima,
                                   item.leadtime,
                                   item.plate_number,
                                   item.layanan,
                                   item.koli,
                                   item.satuan,
                                   item.berat,
                                   item.volume,
                                   arrivalDate,
                                   item.dto,
                                   item.lt_actual,
                                   item.performance,
                                   formatRupiah(item.biaya_paket),
                                   formatRupiah(item.biaya_packing),
                                   formatRupiah(item.insurance),
                                   formatRupiah(item.surcharge),
                                   totalCost,
                                   month,
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
                         tabel.order([2, 'asc']).draw();
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          }

          $('#filterButton').on('click', function() {
               tampilkanData();
          });

          $('#pdfButton').on('click', function() {
               let date_start = convertDate($('#date_start').val());
               let date_end = convertDate($('#date_end').val());
               let pengirim = $('#pengirim').val();
               let penerima = $('#penerima').val();
               let layanan = $('#layanan').val();
               let performance = $('#performance').val();

               var query = $.param({
                    date_start: date_start,
                    date_end: date_end,
                    pengirim: pengirim,
                    penerima: penerima,
                    layanan: layanan,
                    performance: performance,
                    type: 'report'
               });

               window.open("<?= base_url(); ?>laporan_pengiriman/export_pdf_liugong?" + query, '_blank');
          });


     });
</script>


<?= $this->endSection(); ?>
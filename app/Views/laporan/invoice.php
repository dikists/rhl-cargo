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
                         <h6 class="m-0 font-weight-bold text-black">
                              <?= $title; ?>
                         </h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <form class="mb-2">
                              <div class="row my-2">
                                   <div class="col-sm-4">
                                        <label for="date_start">Tanggal Awal</label>
                                        <input type="text" class="form-control datepicker_id" id="date_start" name="date_start" value="<?= date('01-m-Y'); ?>">
                                   </div>
                                   <div class="col-sm-4">
                                        <label for="date_end">Tanggal Akhir</label>
                                        <input type="text" class="form-control datepicker_id" id="date_end" name="date_end" value="<?= date('d-m-Y'); ?>">
                                   </div>
                                   <div class="col-sm-4">
                                        <label for="shipper">Bill To</label>
                                        <select id="pengirim" name="pengirim" class="form-control mySelect2" required>
                                             <option selected value="">Semua</option>
                                             <?php foreach ($pengirim as $data) : ?>
                                                  <option value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>
                              <center>
                                   <button type="button" class="btn btn-primary my-2 btn-sm" id="filterButton"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</button>
                                   <button type="button" class="btn btn-danger my-2 btn-sm" id="pdfButton"><i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF</button>
                                   <button type="button" class="btn btn-success my-2 btn-sm" id="excelButton"><i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel</button>
                              </center>
                         </form>
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%;">
                                   <thead>
                                        <tr>
                                             <th class="text-center" width="3%">No</th>
                                             <th class="text-center" width="8%">Tanggal</th>
                                             <th class="text-center" width="8%">Invoice</th>
                                             <th class="text-center" width="8%">No Faktur</th>
                                             <th class="text-center" width="15%">Bill To</th>
                                             <th class="text-center" width="8%">Total</th>
                                             <th class="text-center" width="5%">PPN</th>
                                             <th class="text-center" width="5%">PPH</th>
                                             <th class="text-center" width="5%">Grand Total</th>
                                             <th class="text-center" width="5%">Status</th>
                                             <th class="text-center" width="8%">Note</th>
                                        </tr>
                                   </thead>
                                   <tbody style="text-align: center;">
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
               processing: true,
               language: {
                    processing: "Loading...",
                    loadingRecords: "Loading data..."
               }
          });
          tampilkanData();

          $('#filterButton').on('click', function() {
               tampilkanData();
          });

          function tampilkanData() {
               let date_start = convertDate($('#date_start').val());
               let date_end = convertDate($('#date_end').val());
               let pengirim = $('#pengirim').val();

               $.ajax({
                    url: "<?= base_url(); ?>data/getInvoice",
                    method: "GET",
                    data: {
                         date_start: date_start,
                         date_end: date_end,
                         pengirim: pengirim
                    },
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         let totalppn = 0;
                         let totalpph = 0;
                         data.forEach(function(item, index) {
                              let total_amount = parseFloat(item.total_amount) || 0;
                              let ppn = parseFloat(item.ppn) || 0;
                              let pph = parseFloat(item.pph) || 0;

                              let totalppn = total_amount * ppn / 100;
                              let totalpph = total_amount * pph / 100;
                              let issue = moment(item.issue_date).format('DD-MM-YYYY');
                              let rowData = [
                                   item.no,
                                   issue,
                                   '#' + item.invoice_number,
                                   item.tax_invoice_number,
                                   item.nama_pelanggan,
                                   formatRupiah(total_amount),
                                   formatRupiah(totalppn),
                                   formatRupiah(totalpph),
                                   formatRupiah((total_amount + totalppn) - totalpph),
                                   item.status,
                                   item.notes
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

          $('#pdfButton').on('click', function() {
               let date_start = convertDate($('#date_start').val());
               let date_end = convertDate($('#date_end').val());
               let pengirim = $('#pengirim').val();
               window.open("<?= base_url(); ?>laporan_invoice/export_pdf?date_start=" + date_start + "&date_end=" + date_end + "&pengirim=" + pengirim, '_blank');
          });
          $('#excelButton').on('click', function() {
               let date_start = convertDate($('#date_start').val());
               let date_end = convertDate($('#date_end').val());
               let pengirim = $('#pengirim').val();
               window.open("<?= base_url(); ?>laporan_invoice/export_excel?date_start=" + date_start + "&date_end=" + date_end + "&pengirim=" + pengirim, '_blank');
          });
     });
</script>


<?= $this->endSection(); ?>
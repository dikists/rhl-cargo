<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">Invoice</h1>
          <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
     </div>

     <!-- Content Row -->
     <div class="row">
          <div class="col-xl-12 col-lg-12">
               <div class="card shadow mb-4 border-primary">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-primary">
                         <h6 class="m-0 font-weight-bold text-primary">Daftar Invoice</h6>
                    </div>
                    <div class="card-body">
                         <div class="table-responsive">
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead>
                                        <tr>
                                             <th>No</th>
                                             <th scope="col">Tanggal Pembuatan</th>
                                             <th scope="col">Tanggal Jatuh Tempo</th>
                                             <th scope="col">No Invoice</th>
                                             <th scope="col">No Faktur</th>
                                             <th scope="col">Amount</th>
                                             <th scope="col">Status</th>
                                             <th scope="col" width="15%" class="text-center">Action</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <div id="noDataMessage" class="text-center" style="display: none;">Data tidak ditemukan.</div>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>

</div>
<!-- /.container-fluid -->
<script>
     $(document).ready(function() {
          var tabel = $('#dt1').DataTable();

          tampilkanData();

          $('#filterButton').on('click', function() {
               tampilkanData();
          });

          function tampilkanData() {
               $.ajax({
                    url: "<?= base_url(); ?>invoice/get_invoices",
                    method: "GET",
                    data: {},
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();

                         if (data.length === 0) {
                              $('#noDataMessage').show();
                         } else {
                              $('#noDataMessage').hide();

                              data.forEach(function(item, index) {

                                   let issue = moment(item.issue_date).format('DD-MM-YY');
                                   let due = moment(item.due_date).format('DD-MM-YY');
                                   let total = formatRupiah(parseFloat(item.total_amount));
                                   // let btnTracking = '<a href="<?= base_url(); ?>tracking/detail/' + item.no_surat_jalan + '" class="btn btn-primary btn-sm"><i class="fa fa-search" aria-hidden="true"></i> Tracking</a>';

                                   let rowData = [
                                        item.no,
                                        issue,
                                        due,
                                        item.invoice_number,
                                        item.rt_number,
                                        total,
                                        item.status,
                                        item.action,
                                   ];

                                   let rowNode = tabel.row.add(rowData).draw(false).node();

                                   // if (item.performance === 'Not Ontime') {
                                   //     $(rowNode).addClass('bg-pending');
                                   // } else if (item.performance === 'Ontime') {
                                   //     $(rowNode).addClass('bg-ontime');
                                   // }
                              });
                              tabel.order([0, 'asc']).draw();
                         }
                    },
                    error: function() {
                         $('#noDataMessage').text('Terjadi kesalahan saat mengambil data.').show();
                    }
               });
          }
     });
</script>
<?= $this->endSection(); ?>
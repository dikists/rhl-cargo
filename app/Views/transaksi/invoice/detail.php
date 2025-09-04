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
                         <h5 class="m-0 font-weight-bold text-black">
                              <?= $title; ?> : <?= $invoice['invoice_number']; ?><br>
                              Issue Date : <?= date('d-m-Y', strtotime($invoice['issue_date'])); ?><br>
                              Bill To : <?= $invoice['nama_pelanggan']; ?>
                         </h5>

                         <div class="float-right">
                              <a href="<?= base_url('admin/invoice'); ?>" class="btn btn-warning text-dark"><i class="fas fa-arrow-left"></i> Kembali</a>
                              <button class="btn btn-success" data-toggle="modal" data-target="#modalTambahDetail"><i class="fas fa-plus"></i> Tambah Detail</button>
                         </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                         <?php if (session()->getFlashdata('success')): ?>
                              <div class="alert alert-success">
                                   <?= session()->getFlashdata('success') ?>
                              </div>
                         <?php endif; ?>

                         <div class="table-responsive">
                              <table id="dt2" class="table table-bordered table-sm" style="width:100%">
                                   <thead style="font-size: 13px;">
                                        <tr>
                                             <th scope="col" width="5%">No</th>
                                             <th scope="col" width="10%">Tanggal Order</th>
                                             <th scope="col" width="10%">No Resi</th>
                                             <th scope="col" width="15%">Deskripsi</th>
                                             <th scope="col" width="8%">Jumlah</th>
                                             <th scope="col" width="8%">Berat</th>
                                             <th scope="col" width="8%">Price</th>
                                             <th scope="col" width="8%">Packing</th>
                                             <th scope="col" width="8%">Surcharge</th>
                                             <th scope="col" width="8%">Asuransi</th>
                                             <th scope="col" width="15%">Biaya Tambahan</th>
                                             <th scope="col" width="8%">Total Cost</th>
                                             <th>#</th>
                                        </tr>
                                   </thead>
                                   <tbody style="font-size: 13px;">
                                   </tbody>
                                   <tfoot style="font-size: 13px;">
                                        <tr>
                                             <th colspan="11" style="text-align:right">Total :</th>
                                             <th colspan="2" id="totalCostFooter" style="text-align:right"></th>
                                        </tr>
                                        <tr>
                                             <th colspan="11" style="text-align:right">PPN <?= $invoice['ppn']; ?> :</th>
                                             <th colspan="2" id="ppnCostFooter" style="text-align:right"></th>
                                        </tr>
                                        <tr>
                                             <th colspan="11" style="text-align:right">PPH <?= $invoice['pph']; ?> :</th>
                                             <th colspan="2" id="pphCostFooter" style="text-align:right"></th>
                                        </tr>
                                        <tr>
                                             <th colspan="11" style="text-align:right">Grand Total :</th>
                                             <th colspan="2" id="grandTotalCostFooter" style="text-align:right"></th>
                                        </tr>
                                   </tfoot>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalTambahDetail" tabindex="-1" aria-labelledby="modalTambahDetailLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDetailLabel">Daftar Pengiriman Per Billto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <div class="table-responsive">
                         <table id="dt1" class="table table-bordered table-hover table-sm" style="width:100%">
                              <thead style="font-size: 13px;">
                                   <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col" width="10%">Tanggal Order</th>
                                        <th scope="col" width="10%">No Resi</th>
                                        <th scope="col" width="15%">Pengirim</th>
                                        <th scope="col" width="15%">Penerima</th>
                                        <th scope="col" width="10%">Layanan</th>
                                        <th scope="col" width="10%">Vendor</th>
                                        <th scope="col" width="10%">Sub Vendor</th>
                                        <th scope="col" width="10%">Jumlah</th>
                                        <th scope="col" width="10%">Berat</th>
                                   </tr>
                              </thead>
                              <tbody style="font-size: 13px;">
                              </tbody>
                         </table>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               </div>
          </div>
     </div>
</div>


<script>
     $(document).ready(function() {
          var tabel2 = $('#dt2').DataTable({
               processing: true,
               language: {
                    processing: "Loading...",
                    loadingRecords: "Loading data..."
               }
          });
          var tabel = $('#dt1').DataTable({
               processing: true,
               language: {
                    processing: "Loading...",
                    loadingRecords: "Loading data..."
               }
          });
          tampilkanData2();

          $('#modalTambahDetail').on('show.bs.modal', function(e) {
               tampilkanData();
          });

          function tampilkanData() {
               var id_pelanggan = "<?= $invoice['id_pelanggan']; ?>";

               $.ajax({
                    url: "<?= base_url(); ?>data/getPengirimanToInvoice/" + id_pelanggan,
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');
                              let rowData = [
                                   item.no,
                                   item.date,
                                   item.no_surat_jalan,
                                   item.nama_pelanggan,
                                   item.nama_penerima,
                                   item.layanan,
                                   item.vendor_name,
                                   item.sub_vendor_name,
                                   item.koli + ' Koli',
                                   item.berat + ' KG',
                              ];
                              let rowNode = tabel.row.add(rowData).draw(false).node();
                              $(rowNode).attr('data-id-pengiriman', item.id_pengiriman);
                         });
                         tabel.order([0, 'asc']).draw();
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          }

          function tampilkanData2() {
               let id_invoice = "<?= $invoice['id']; ?>";

               $.ajax({
                    url: "<?= base_url(); ?>data/getDetailInvoice/" + id_invoice,
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         let total = 0;
                         let totalCost = 0;
                         tabel2.clear().draw();
                         let ppn = "<?= $invoice['ppn']; ?>";
                         let pph = "<?= $invoice['pph']; ?>";

                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');
                              total = (parseFloat(item.berat) || 0) * (parseFloat(item.price) || 0) +
                                   (parseFloat(item.biaya_packing) || 0) +
                                   (parseFloat(item.surcharge) || 0) +
                                   (parseFloat(item.insurance) || 0);
                              // jika bill type flat
                              if (item.bill_type == 'flat') {
                                   total = (parseFloat(item.price) || 0);
                              }
                              total = total + (parseFloat(item.total_biaya_lain) || 0);
                              totalCost += total;

                              let rowData = [
                                   item.no,
                                   item.date,
                                   item.no_surat_jalan,
                                   item.nama_pelanggan + ' - ' + item.nama_penerima,
                                   item.koli + ' Koli',
                                   parseFloat(item.berat.toFixed(2)) + ' KG',
                                   formatRupiah(item.price),
                                   formatRupiah(item.biaya_packing),
                                   formatRupiah(item.surcharge),
                                   formatRupiah(item.insurance),
                                   item.extra_charge,
                                   formatRupiah(total),
                                   item.aksi
                              ];
                              let rowNode = tabel2.row.add(rowData).draw(false).node();
                              $(rowNode).attr('data-id-pengiriman', item.id_pengiriman);
                         });
                         tabel2.order([0, 'asc']).draw();
                         $('#totalCostFooter').html(formatRupiah(totalCost));
                         let totalppn = totalCost * ppn / 100;
                         let totalpph = totalCost * pph / 100;
                         $('#ppnCostFooter').html(formatRupiah(totalppn));
                         $('#pphCostFooter').html(formatRupiah(totalpph));
                         $('#grandTotalCostFooter').html(formatRupiah((totalCost + totalppn) - totalpph));
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          }

          $('#dt1 tbody').on('click', 'tr', function() {
               let id_pengiriman = $(this).attr('data-id-pengiriman');
               let id_invoice = "<?= $invoice['id']; ?>";

               $.ajax({
                    url: "<?= base_url(); ?>data/insertDetailInvoice",
                    method: "POST",
                    data: {
                         id_pengiriman: id_pengiriman,
                         id_invoice: id_invoice
                    },
                    dataType: 'json',
                    success: function(response) {
                         if (response.success) {
                              alert('Data berhasil disimpan!');
                              tampilkanData();
                              tampilkanData2();
                              $('#modalTambahDetail').modal('hide');
                         } else {
                              alert('Gagal menyimpan data.');
                         }
                    },
                    error: function() {
                         alert('Terjadi kesalahan pada server.');
                    }
               });
          });

          $(document).on('click', '.deleteDetailInvoice', function(e) {
               let id_detail_invoice = $(this).data('id');
               let invoice_id = $(this).data('invoice_id');

               $.ajax({
                    url: "<?= base_url(); ?>data/deleteDetailInvoice/" + id_detail_invoice,
                    method: "POST",
                    data: {
                         invoice_id: invoice_id,
                         id_detail_invoice: id_detail_invoice
                    },
                    dataType: 'json',
                    success: function(response) {
                         if (response.success) {
                              alert(response.message);
                              tampilkanData();
                              tampilkanData2();
                         } else {
                              alert(response.message);
                         }
                    },
                    error: function() {
                         alert('Terjadi kesalahan pada server.');
                    }
               });
          });

     });
</script>

<?= $this->endSection(); ?>
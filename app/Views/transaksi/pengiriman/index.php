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
                         <a href="<?= base_url('admin/pengambilan/add'); ?>" class="btn btn-success btn-sm">
                              <i class="fas fa-plus"></i> Tambah data
                         </a>
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
                                   <thead style="font-size: 13px;">
                                        <tr>
                                             <th scope="col" style="font-size: 11px" class="text-center">No</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Tanggal Kirim</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">No Resi</th>
                                             <th scope="col" style="font-size: 11px" class="text-center" width="10%">Pengirim</th>
                                             <th scope="col" style="font-size: 11px" class="text-center" width="10%">Penerima</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Driver</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Layanan</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Vendor</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Sub Vendor</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Jumlah</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Berat</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Status</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">DTO</th>
                                             <th scope="col" style="font-size: 11px" class="text-center" width="10%">Remark</th>
                                             <th scope="col" style="font-size: 11px" class="text-center">Aksi</th>
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

<!-- Modal Remark-->
<div class="modal fade" id="modalRemark" tabindex="-1" aria-labelledby="modalRemarkLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalRemarkLabel">Keterangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <form>
                         <div class="form-group">
                              <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"></textarea>
                              <input type="hidden" class="form-control" id="id_pengiriman" autocomplete="off">
                         </div>
                    </form>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateRemark">Update</button>
               </div>
          </div>
     </div>
</div>
<!-- Modal Asuransi-->
<div class="modal fade" id="modalAsuransi" tabindex="-1" aria-labelledby="modalAsuransiLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalAsuransiLabel">Asuransi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <form>
                         <div class="form-group">
                              <input type="text" name="insurance" id="insurance" cols="30" rows="5" class="form-control number"></input>
                              <input type="hidden" class="form-control" id="id_pengiriman" autocomplete="off">
                         </div>
                    </form>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateInsurance">Update</button>
               </div>
          </div>
     </div>
</div>

<!-- Modal Asuransi-->
<div class="modal fade" id="modalSurcharge" tabindex="-1" aria-labelledby="modalSurchargeLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalSurchargeLabel">Surcharge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <form>
                         <div class="form-group">
                              <input type="text" name="surcharge" id="surcharge" cols="30" rows="5" class="form-control number"></input>
                              <input type="hidden" class="form-control" id="id_pengiriman" autocomplete="off">
                         </div>
                    </form>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateSurcharge">Update</button>
               </div>
          </div>
     </div>
</div>

<!-- Modal Detail Barang -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <div class="table-responsive">
                         <table id="detailTable" class="table table-bordered table-sm" style="width:100%">
                              <thead style="font-size: 14px; text-align: center; font-weight: 700">
                                   <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">P</th>
                                        <th scope="col">L</th>
                                        <th scope="col">T</th>
                                        <th scope="col">Volume</th>
                                        <th scope="col">Berat</th>
                                        <th scope="col">Berat Volume</th>
                                        <th scope="col">Total Hitung</th>
                                   </tr>
                              </thead>
                              <tbody style="font-size: 13px; text-align: center; font-weight: 700" class="text-dark">
                              </tbody>
                              <tfoot style="font-size: 13px; text-align: center; font-weight: 700" class="text-dark">
                              </tfoot>
                         </table>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="#" target="_blank" id="editLink" class="btn btn-success">
                         <i class="fas fa-edit"></i> Edit Data
                    </a>
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
                    url: "<?= base_url(); ?>data/getPengiriman",
                    method: "GET",
                    data: {
                         date_start: date_start,
                         date_end: date_end,
                         pengirim: pengirim,
                         penerima: penerima,
                         layanan: layanan
                    },
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');
                              let remark = item.remark ?
                                   '<a href="#" data-toggle="modal" data-target="#modalRemark" data-remark="' + item.remark + '" data-id="' + item.id_pengiriman + '" class="text-center">' + item.remark + '</a>' :
                                   '<a href="#" data-toggle="modal" data-target="#modalRemark" data-remark="' + item.remark + '" data-id="' + item.id_pengiriman + '" class="text-center">----</a>';
                              remark += '<br> Asuransi : ' + formatRupiah(item.insurance);
                              remark += '<br> Surcharge : ' + formatRupiah(item.surcharge);
                              remark += '<br> Biaya Lain : ' + formatRupiah(item.additional_cost);
                              let rowData = [
                                   item.no,
                                   item.date,
                                   item.surat_jalan,
                                   item.nama_pelanggan,
                                   item.nama_penerima,
                                   item.driver_name.toUpperCase(),
                                   item.layanan,
                                   item.vendor_name,
                                   item.sub_vendor_name,
                                   item.koli + ' ' + item.satuan,
                                   item.berat + ' KG',
                                   item.status,
                                   item.dto,
                                   remark,
                                   item.aksi,
                              ];
                              let rowNode = tabel.row.add(rowData).draw(false).node();
                              if (item.in_invoice == 1) {
                                   $(rowNode).css('background-color', '#F0F8FF');
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


               // document.location.href = '/pdf/cetak_label/' + id + "/" + qty;
               var myWindow = window.open('/pdf/cetak_label/' + id + "/" + qty, '_blank');

               setTimeout(() => {
                    $('#modalCetakLabel').modal('hide');
                    $('#qtyCetak').val('');

                    // myWindow.close();
                    // document.location.href = '';
               }, 3000);

          });

          $('#modalRemark').on('show.bs.modal', function(event) {
               var button = $(event.relatedTarget);
               var id = button.data('id');
               var remark = button.data('remark');
               $('#id_pengiriman').val(id);
               $('#keterangan').val(remark);
          });

          $('#modalAsuransi').on('show.bs.modal', function(event) {
               var button = $(event.relatedTarget);
               var id = button.data('id');
               var insurance = button.data('insurance');
               $('#id_pengiriman').val(id);
               $('#insurance').val(insurance);
          });

          $('#modalSurcharge').on('show.bs.modal', function(event) {
               var button = $(event.relatedTarget);
               var id = button.data('id');
               var surcharge = button.data('surcharge');
               $('#id_pengiriman').val(id);
               $('#surcharge').val(surcharge);
          });

          $('#modalDetail').on('show.bs.modal', function(event) {
               var button = $(event.relatedTarget);
               var id = button.data('id');
               var idOrder = button.data('idOrder');
               var divider = button.data('divider');
               var minimum = button.data('minimum');

                // Update href pada tombol Edit
               $('#editLink').attr('href', "<?= base_url('admin/order/barang/'); ?>" + idOrder);

               console.log(idOrder, divider, minimum);
               $.ajax({
                    url: "<?= base_url(); ?>data/getDetailOrder/" + idOrder,
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let totalJumlah = 0;
                         let totalVolume = 0;
                         let totalBerat = 0;
                         let totalBeratVolume = 0;
                         let totalHitungKeseluruhan = 0;

                         let tbodyHtml = '';

                         response.forEach(function(item, index) {
                              let beratVolume = Math.ceil(item.volume / divider);
                              let totalHitung = (item.berat > beratVolume) ? item.berat : beratVolume;

                              totalJumlah += parseInt(item.jumlah, 10);
                              totalVolume += parseFloat(item.volume);
                              totalBerat += parseFloat(item.berat);
                              totalBeratVolume += beratVolume;
                              totalHitungKeseluruhan += totalHitung * item.jumlah;

                              let rowColor = item.has_packing == 1 ? ' style="background-color: #FFE4C4;"' : '';

                              tbodyHtml += `
                                             <tr${rowColor}>
                                                  <td>${index + 1}</td>
                                                  <td>${item.barang}</td>
                                                  <td>${item.jumlah} ${item.satuan}</td>
                                                  <td>${item.panjang} CM</td>
                                                  <td>${item.lebar} CM</td>
                                                  <td>${item.tinggi} CM</td>
                                                  <td>${item.volume} CM3</td>
                                                  <td>${item.berat} KG</td>
                                                  <td>${beratVolume} KG</td>
                                                  <td>${totalHitung * item.jumlah} KG</td>
                                             </tr>
                                        `;
                         });

                         let totalHitungText = totalHitungKeseluruhan < minimum ?
                              `${minimum} KG (Charge Minimum)` :
                              `${totalHitungKeseluruhan.toFixed(2)} KG`;

                         // Tampilkan isi <tbody>
                         $('#detailTable tbody').html(tbodyHtml);

                         // Tampilkan <tfoot>
                         $('#detailTable tfoot').html(`
                                             <tr>
                                                  <th colspan="2">Total</th>
                                                  <th>${totalJumlah}</th>
                                                  <th colspan="3"></th>
                                                  <th>${totalVolume.toFixed(2)} CM3</th>
                                                  <th>${totalBerat.toFixed(2)} KG</th>
                                                  <th>${totalBeratVolume.toFixed(2)} KG</th>
                                                  <th>${totalHitungText}</th>
                                             </tr>
                                        `);
                    },
                    error: function() {
                         alert('Terjadi kesalahan saat mengambil data.');
                    }
               });

          });

          $('#updateRemark').on('click', function() {
               var id = $('#id_pengiriman').val();
               var remark = $('#keterangan').val();

               $.ajax({
                    url: '/data/updateRemarkPengiriman/' + id,
                    type: 'POST',
                    data: {
                         _method: 'PUT',
                         csrf_token_name: $('meta[name="csrf-token"]').attr('content'),
                         remark: remark
                    },
                    success: function(response) {
                         // alert(response['message']);
                         if (response['status'] == 'success') {
                              $('#modalRemark').modal('hide');
                              tampilkanData();
                         }
                    },
                    error: function(xhr) {
                         alert('Terjadi kesalahan!');
                    }
               });
          });
          $('#updateInsurance').on('click', function() {
               var id = $('#id_pengiriman').val();
               var insurance = $('#insurance').val();

               $.ajax({
                    url: '/data/updateInsurancePengiriman/' + id,
                    type: 'POST',
                    data: {
                         _method: 'PUT',
                         csrf_token_name: $('meta[name="csrf-token"]').attr('content'),
                         insurance: insurance
                    },
                    success: function(response) {
                         // alert(response['message']);
                         if (response['status'] == 'success') {
                              $('#modalAsuransi').modal('hide');
                              tampilkanData();
                         }
                    },
                    error: function(xhr) {
                         alert('Terjadi kesalahan!');
                    }
               });
          });
          $('#updateSurcharge').on('click', function() {
               var id = $('#id_pengiriman').val();
               var surcharge = $('#surcharge').val();

               $.ajax({
                    url: '/data/updateSurchargePengiriman/' + id,
                    type: 'POST',
                    data: {
                         _method: 'PUT',
                         csrf_token_name: $('meta[name="csrf-token"]').attr('content'),
                         surcharge: surcharge
                    },
                    success: function(response) {
                         // alert(response['message']);
                         if (response['status'] == 'success') {
                              $('#modalSurcharge').modal('hide');
                              tampilkanData();
                         }
                    },
                    error: function(xhr) {
                         alert('Terjadi kesalahan!');
                    }
               });
          });

     });
</script>


<?= $this->endSection(); ?>
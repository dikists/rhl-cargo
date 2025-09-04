<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <div class="text-center">
          <h1 class="h3 mb-0 text-gray-800"><?= $order['nama_pelanggan'] ?> - <?= $order['nama_penerima'] ?></h1>
          <h1 class="h4 mb-0 text-gray-800"><?= $order['no_order'] ?></h1>
     </div>

     <div class="row mb-3">

          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">
               <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                         <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>
                         <div class="float-right">
                              <a href="<?= base_url('admin/order/all'); ?>" class="btn btn-warning btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                              <?php
                              $role = session()->get('role');
                              if ($role !== 'PIC RELASI') {
                              ?>
                                   <a href="<?= base_url('admin/order/barang/'); ?><?= $order['id_order'] ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-edit"></i> Edit Data
                                   </a>
                              <?php
                              }
                              ?>
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
                              <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                   <thead>
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
                                   <tbody>
                                   </tbody>
                                   <tfoot>
                                   </tfoot>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<script>
     $(document).ready(function() {
          let id_order = "<?= $order['id_order'] ?>";
          let divider = "<?= $order['divider'] ?>";
          let minimum = "<?= $order['minimum'] ?>";
          var tabel = $('#dt1').DataTable();
          tampilkanData();

          function tampilkanData() {

               $.ajax({
                    url: "<?= base_url(); ?>data/getDetailOrder/" + id_order,
                    method: "GET",
                    dataType: 'json',
                    success: function(response) {
                         let data = response;
                         let totalJumlah = 0;
                         let totalVolume = 0;
                         let totalBerat = 0;
                         let totalBeratVolume = 0;
                         let totalHitungKeseluruhan = 0;

                         tabel.clear().draw();
                         data.forEach(function(item, index) {
                              let beratVolume = Math.ceil(item.volume / divider);
                              let totalHitung = (item.berat > beratVolume) ? item.berat : beratVolume;
                              let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');

                              totalJumlah += parseInt(item.jumlah, 10);
                              totalVolume += parseFloat(item.volume);
                              totalBerat += parseFloat(item.berat);
                              totalBeratVolume += beratVolume;
                              totalHitungKeseluruhan += totalHitung * item.jumlah;

                              let rowData = [
                                   index + 1,
                                   item.barang,
                                   item.jumlah + ' ' + item.satuan,
                                   item.panjang + ' CM',
                                   item.lebar + ' CM',
                                   item.tinggi + ' CM',
                                   item.volume + ' CM3',
                                   item.berat + ' KG',
                                   beratVolume + ' KG',
                                   totalHitung * item.jumlah + ' KG',
                              ];
                              let rowNode = tabel.row.add(rowData).draw(false).node();

                              if (item.has_packing == 1) {
                                   $(rowNode).css('background-color', '#FFE4C4'); // Contoh warna emas
                              }
                         });
                         if (totalHitungKeseluruhan < minimum) {
                              totalHitungKeseluruhan = minimum + ' KG (Charge Minimum)';
                         }

                         $('#dt1 tfoot').html(`
                              <tr>
                                   <th colspan="2">Total</th>
                                   <th>${totalJumlah}</th>
                                   <th colspan="3"></th>
                                   <th>${totalVolume} CM3</th>
                                   <th>${totalBerat} KG</th>
                                   <th>${totalBeratVolume} KG</th>
                                   <th>${totalHitungKeseluruhan} </th>
                              </tr>
                         `);
                         tabel.order([0, 'asc']).draw();
                    },
                    error: function() {
                         alert('Terjadi kesalahan');
                    }
               });
          }

     });
</script>


<?= $this->endSection(); ?>
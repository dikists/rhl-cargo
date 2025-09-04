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
          <a href="<?= base_url('admin/order/all'); ?>" class="btn btn-warning btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>

        <!-- Card Body -->
        <div class="card-body">
          <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
              <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= $error ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form action="<?= base_url('data/editOrder') ?>" method="post">
            <input type="text" name="id_order" id="id_order" value="<?= $order['id_order']; ?>" hidden readonly required>
            <?= csrf_field(); ?>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="billto">Billto</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="billto" name="billto" placeholder="Nama billto" value="<?= old('billto', ($order['billto'] ?? '')); ?>" readonly required>
                  <input type="hidden" name="idBillto" id="idBillto" value="<?= old('idBillto', ($order['id_billto'] ?? '')); ?>">
                  <div class="input-group-prepend">
                    <div class="input-group-text btn bg-info font-weight-bold text-white" data-toggle="modal" data-target="#dataBillto"><i class="fas fa-search fa-sm"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="pengirim">Pengirim</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="pengirim" name="pengirim" placeholder="Nama Pengirim" value="<?= old('pengirim', ($order['nama_pelanggan'] ?? '')); ?>" readonly required>
                  <input type="hidden" name="idPengirim" id="idPengirim" value="<?= old('idPengirim', ($order['id_pelanggan'] ?? '')); ?>">
                  <div class="input-group-prepend">
                    <div class="input-group-text btn bg-info font-weight-bold text-white" data-toggle="modal" data-target="#dataPengirim"><i class="fas fa-search fa-sm"></i></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label for="penerima">Nama Penerima</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="penerima" name="penerima" value="<?= old('penerima', ($order['nama_penerima'] ?? '')); ?>" readonly placeholder="Pilih Pengirim Terlebih Dahulu">
                  <input type="hidden" class="form-control" id="idPenerima" name="idPenerima" value="<?= old('idPenerima', ($order['id_penerima'] ?? '')); ?>">
                  <div class="input-group-prepend">
                    <div class="input-group-text btn bg-gradient-info font-weight-bold text-white" data-toggle="modal" data-target="#dataPenerima"><i class="fas fa-search fa-sm"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="date">Tanggal</label>
                <input type="text" class="form-control datepicker" id="date" name="date" autocomplete="off" value="<?= $order['tanggal_order']; ?>">
              </div>
              <div class="form-group col-md-4">
                <label for="no_ref">Nomor Referensi</label>
                <div class="input-group">
                  <?php
                  $no = explode('/', $order['no_ref'], 2);
                  ?>
                  <input type="text" class="form-control" id="no_ref" name="no_ref" placeholder="Nomor Referensi" value="<?= old('no_ref', ($no[0])); ?>">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><?= $no[1]; ?></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <label for="pilihLayanan">Pilih Layanan</label>
                <select id="pilihLayanan" name="layanan" class="form-control">
                  <option value="">Pilih Layanan</option>
                  <?php foreach ($layanan as $l) : ?>
                    <?php if ($order['id_layanan'] == $l['id_layanan']) : ?>
                      <option value="<?= $l['id_layanan']; ?>" selected><?= $l['layanan']; ?> (<?= $l['biaya_paket']; ?>)</option>
                    <?php else : ?>
                      <option value="<?= $l['id_layanan']; ?>"><?= $l['layanan']; ?> (<?= $l['biaya_paket']; ?>)</option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <button class="btn btn-sm btn-primary" type="submit" name="save">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal billto-->
<div class="modal fade" id="dataBillto" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="dataBilltoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dataBilltoLabel">Pilih Data Billto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover table-sm dataTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Telepon</th>
                <th scope="col">Kota</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach ($pengirim as $p) : ?>
                <tr class="dataBillto" data-namaBillto="<?= $p['nama_pelanggan']; ?>" data-idBillto="<?= $p['id_pelanggan']; ?>" data-dismiss="modal">
                  <th><?= $no++; ?></th>
                  <td><?= $p['nama_pelanggan']; ?></td>
                  <td><?= $p['telepon_pelanggan']; ?></td>
                  <td><?= $p['kota']; ?></td>
                </tr>
              <?php endforeach; ?>
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

<!-- Modal Pengirim-->
<div class="modal fade" id="dataPengirim" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="dataPengirimLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dataPengirimLabel">Pilih Data Pengirim Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover table-sm" id="dataTableDua">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Telepon</th>
                <th scope="col">Kota</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach ($pengirim as $p) : ?>
                <tr class="dataPengirim" data-namaPengirim="<?= $p['nama_pelanggan']; ?>" data-idPengirim="<?= $p['id_pelanggan']; ?>" data-dismiss="modal">
                  <th><?= $no++; ?></th>
                  <td><?= $p['nama_pelanggan']; ?></td>
                  <td><?= $p['telepon_pelanggan']; ?></td>
                  <td><?= $p['kota']; ?></td>
                </tr>
              <?php endforeach; ?>
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

<!-- Modal Penerima -->
<div class="modal fade" id="dataPenerima" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="dataPenerimaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dataPenerimaLabel">Pilih Data Penerima Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <div id="tabelPenerima"></div> -->
        <div class="table-responsive">
          <table id="tabelPenerima" class="table table-hover table-sm" style="width:100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Penerima</th>
                <th>Telepon Penerima</th>
                <th>Kota</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php foreach ($penerima as $p) : ?>
                <tr class="dataPenerima" data-namaPenerima="<?= $p['nama_penerima']; ?>" data-idPenerima="<?= $p['id_penerima']; ?>" data-dismiss="modal">
                  <th><?= $no++; ?></th>
                  <td><?= $p['nama_penerima']; ?></td>
                  <td><?= $p['telepon_penerima']; ?></td>
                  <td><?= $p['kabupaten']; ?></td>
                </tr>
              <?php endforeach; ?>
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
    $('.dataBillto').click(function() {
      let id_billto = $(this).attr('data-idBillto');
      let nama_billto = $(this).attr('data-namaBillto');
      $('#billto').val(nama_billto);
      $('#idBillto').val(id_billto);
    });
    $('.dataPengirim').click(function() {
      let id_pengirim = $(this).attr('data-idPengirim');
      let nama_pengirim = $(this).attr('data-namaPengirim');
      $('#pengirim').val(nama_pengirim);
      $('#idPengirim').val(id_pengirim);
      $.ajax({
        url: "<?= base_url(); ?>data/get_penerima_by_pengirim/" + id_pengirim,
        method: "GET",
        success: function(response) {
          let data = JSON.parse(response);
          let table = $('#tabelPenerima').DataTable();
          table.clear();
          data.forEach(function(item, index) {
            let rowNode = table.row.add([
              index + 1,
              item.nama_penerima,
              item.telepon_penerima,
              item.kabupaten,
            ]).draw().node();

            $(rowNode).attr('data-idPenerima', item.id_penerima);
            $(rowNode).attr('data-namaPenerima', item.nama_penerima);
            $(rowNode).addClass('dataPenerima');
          });
        }
      });
    });

    $(document).on('click', '.dataPenerima', function() {
      let id_pengirim = $('#idPengirim').val();
      let id_penerima = $(this).attr('data-idPenerima');
      let nama_penerima = $(this).attr('data-namaPenerima');
      console.log(nama_penerima);

      $('#penerima').val(nama_penerima);
      $('#idPenerima').val(id_penerima);
      $('#dataPenerima').modal('hide');
      $.ajax({
        url: "<?= base_url(); ?>data/get_layanan_new_order",
        method: "GET",
        data: {
          id_pengirim: id_pengirim,
          id_penerima: id_penerima
        },
        success: function(response) {
          console.log(response);
          let layanan = $('#pilihLayanan');
          layanan.empty();
          layanan.append('<option value="">Pilih Layanan</option>');
          let dataLayanan = JSON.parse(response);
          dataLayanan.forEach(function(item) {
            let biaya = formatRupiah(parseFloat(item.biaya_paket));
            layanan.append('<option value="' + item.id_layanan + '">' + item.layanan + ' (' + biaya + ')</option>');
          });
        }
      });
    });

    // $('#tabelPenerima tbody').on('click', 'tr', function() {
    //   let table = $('#tabelPenerima').DataTable();
    //   let data = table.row(this).data();
    //   let id_penerima = data[4];
    //   let nama_penerima = data[1];
    //   let id_pengirim = $('#idPengirim').val();
    //   console.log(id_penerima);
    //   $('#penerima').val(nama_penerima);
    //   $('#idPenerima').val(id_penerima);
    //   $('#dataPenerima').modal('hide');
    //   $.ajax({
    //     url: "<?= base_url(); ?>data/get_layanan_new_order",
    //     method: "GET",
    //     data: {
    //       id_pengirim: id_pengirim,
    //       id_penerima: id_penerima
    //     },
    //     success: function(response) {
    //       console.log(response);
    //       let layanan = $('#pilihLayanan');
    //       layanan.empty();
    //       layanan.append('<option value="">Pilih Layanan</option>');
    //       let dataLayanan = JSON.parse(response);
    //       dataLayanan.forEach(function(item) {
    //         let biaya = formatRupiah(parseFloat(item.biaya_paket));
    //         layanan.append('<option value="' + item.id_layanan + '">' + item.layanan + ' (' + biaya + ')</option>');
    //       });
    //     }
    //   });
    // });
  });
</script>


<?= $this->endSection(); ?>
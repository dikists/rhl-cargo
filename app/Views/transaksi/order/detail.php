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
            <a href="<?= base_url('admin/order/add'); ?>" class="btn btn-success btn-sm">
              <i class="fas fa-plus"></i> Tambah Order
            </a>
            <a href="<?= base_url('admin/order/detail/' . $order['id_order']); ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
          </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
              <?= session()->getFlashdata('success') ?>
            </div>
          <?php endif; ?>

          <button type="button" class="btn btn-sm btn-primary mb-2" data-toggle="modal" data-target="#tambahBarang">
            Tambah Barang
          </button>

          <!-- Modal -->
          <div class="modal fade" id="tambahBarang" data-keyboard="false" tabindex="-1" aria-labelledby="tambahBarangLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="tambahBarangLabel">Tambah Barang </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="nama_barang">Nama Barang</label>
                      <input type="hidden" name="id_order" id="id_order" value="<?= $order['id_order'] ?>">
                      <select name="nama_barang" class="form-control mySelect2" id="nama_barang">
                        <option value="">Choose</option>
                        <?php foreach ($barang as $brg) : ?>
                          <option value="<?= $brg['id_barang'] ?>"><?= $brg['nama_barang'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4">
                      <label for="jumlah">Jumlah</label>
                      <input type="number" class="form-control" id="jumlah" name="jumlah" min="0">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="berat">Berat</label>
                      <input type="number" class="form-control" id="berat" name="berat" min="0">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="panjang">Panjang</label>
                      <input type="number" class="form-control" id="panjang" name="panjang" min="0">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="lebar">Lebar</label>
                      <input type="number" class="form-control" id="lebar" name="lebar" min="0">
                    </div>
                    <div class="form-group col-md-4">
                      <label for="tinggi">Tinggi</label>
                      <input type="number" class="form-control" id="tinggi" name="tinggi" min="0">
                    </div>
                  </div>

                  <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="packing" name="packing">
                    <label class="form-check-label" for="packing">Packing ?</label>
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" name="tambah" id="tambah-order-barang" class="btn btn-primary">Tambah</button>
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
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Jumlah</th>
                  <th scope="col">Berat</th>
                  <th scope="col">P</th>
                  <th scope="col">L</th>
                  <th scope="col">T</th>
                  <th scope="col">Volume</th>
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
<!-- Modal Edit Barang -->
<div class="modal fade" id="editBarangModal" tabindex="-1" aria-labelledby="editBarangModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBarangModalLabel">Edit Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="e_nama_barang">Nama Barang</label>
            <input type="hidden" name="id_order" id="id_order" value="<?= $order['id_order'] ?>">
            <input type="hidden" name="id_detail" id="id_detail">
            <select name="e_nama_barang" class="form-control mySelect2" id="e_nama_barang">
              <option value="">Choose</option>
              <?php foreach ($barang as $brg) : ?>
                <option value="<?= $brg['id_barang'] ?>"><?= $brg['nama_barang'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="e_jumlah">Jumlah</label>
            <input type="number" class="form-control" id="e_jumlah" name="e_jumlah" min="0">
          </div>
          <div class="form-group col-md-4">
            <label for="e_berat">Berat</label>
            <input type="number" class="form-control" id="e_berat" name="e_berat" min="0">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="e_panjang">Panjang</label>
            <input type="number" class="form-control" id="e_panjang" name="e_panjang" min="0">
          </div>
          <div class="form-group col-md-4">
            <label for="e_lebar">Lebar</label>
            <input type="number" class="form-control" id="e_lebar" name="e_lebar" min="0">
          </div>
          <div class="form-group col-md-4">
            <label for="e_tinggi">Tinggi</label>
            <input type="number" class="form-control" id="e_tinggi" name="e_tinggi" min="0">
          </div>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="e_packing" name="e_packing">
          <label class="form-check-label" for="e_packing">Packing ?</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="simpanEditBarang">Simpan Perubahan</button>
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
        url: "<?= base_url(); ?>data/getDetailOrder/" + id_order,
        method: "GET",
        dataType: 'json',
        success: function(response) {
          let data = response;
          tabel.clear().draw();
          data.forEach(function(item, index) {
            let tanggalBaru = moment(item.tanggal_order).format('DD-MMMM-YY');
            let rowData = [
              index + 1,
              item.barang,
              item.jumlah,
              item.berat,
              item.panjang,
              item.lebar,
              item.tinggi,
              item.volume,
              `<button class="btn btn-info btn-sm btn-edit m-1" data-id="` + item.id_detail + `" data-jumlah="` + item.jumlah + `" data-berat="` + item.berat + `" data-panjang="` + item.panjang + `" data-lebar="` + item.lebar + `" data-tinggi="` + item.tinggi + `" data-id_barang="` + item.id_barang + `" data-packing="` + item.has_packing + `"><i class="fas fa-edit m-1"></i></button> 
							<button class="btn btn-danger btn-sm btn-hapus m-1" data-id="` + item.id_detail + `"><i class="fas fa-trash m-1"></i></button>`
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
      $('#nama_barang').val(null).trigger('change');
      $('#jumlah').val('');
      $('#berat').val('');
      $('#panjang').val('');
      $('#lebar').val('');
      $('#tinggi').val('');
      $('#packing').prop('checked', false);
    }

    function bersihkanFormEdit() {
      $('#e_nama_barang').val(null).trigger('change');
      $('#id_detail').val('');
      $('#e_jumlah').val('');
      $('#e_berat').val('');
      $('#e_panjang').val('');
      $('#e_lebar').val('');
      $('#e_tinggi').val('');
    }

    $('#tambah-order-barang').click(function(event) {
      event.preventDefault();
      let name = $('#nama_barang').val();
      let jumlah = $('#jumlah').val();
      let berat = $('#berat').val();
      let panjang = $('#panjang').val();
      let lebar = $('#lebar').val();
      let tinggi = $('#tinggi').val();
      let id_order = $('#id_order').val();
      let packing = 0;
      var packingCheckbox = $('#packing');
      if (packingCheckbox.is(':checked')) {
        packing = 1;
      }      

      $('#tambahBarang').modal('hide');

      if (name == "" || jumlah == "" || berat == "" || panjang == "" || lebar == "" || tinggi == "") {
        alert('Data Belum Lengkap');
        return false;
      }

      $.ajax({
        url: "<?= base_url(); ?>data/add_detail_order",
        method: "POST",
        data: {
          id_order: id_order,
          name: name,
          berat: berat,
          jumlah: jumlah,
          panjang: panjang,
          lebar: lebar,
          tinggi: tinggi,
          packing: packing,
        },
        success: function(response) {
          console.log(response);
          if (response == "ok") {
            tampilkanData();
            bersihkanForm();
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
          alert('Terjadi kesalahan saat menambahkan barang!');
        }
      });
    })

    $('#simpanEditBarang').click(function(event) {
			event.preventDefault();
			let name = $('#e_nama_barang').val();
			let jumlah = $('#e_jumlah').val();
			let berat = $('#e_berat').val();
			let panjang = $('#e_panjang').val();
			let lebar = $('#e_lebar').val();
			let tinggi = $('#e_tinggi').val();
			let id_order = $('#id_order').val();
			let id_detail = $('#id_detail').val();
			let packing = 0;
			var packingCheckbox = $('#e_packing');
			if (packingCheckbox.is(':checked')) {
				packing = 1;
			}
			$('#editBarangModal').modal('hide');

      console.log('id_detail: ' + id_detail);
      
			if (name == "" || jumlah == "" || berat == "" || panjang == "" || lebar == "" || tinggi == "") {
				alert('Data Belum Lengkap');
				return false;
			}

			$.ajax({
				url: "<?= base_url(); ?>data/update_detail_order",
				method: "POST",
				data: {
					id_detail: id_detail,
					id_order: id_order,
					name: name,
					berat: berat,
					jumlah: jumlah,
					panjang: panjang,
					lebar: lebar,
					tinggi: tinggi,
					packing: packing,
				},
				success: function(response) {
					console.log(response);
					if (response == "ok") {
						bersihkanFormEdit();
						tampilkanData();
					}
				},
				error: function(xhr, status, error) {
					console.error(xhr.responseText);
					alert('Terjadi kesalahan saat menambahkan barang!');
				}
			});
		})

    $('#dt1').on('click', '.btn-edit', function() {
      // Ambil data dari baris tabel yang sesuai
      let id_detail = $(this).data('id');
      let id_barang = $(this).data('id_barang');
      let barang = $(this).data('barang');
      let jumlah = $(this).data('jumlah');
      let berat = $(this).data('berat');
      let panjang = $(this).data('panjang');
      let lebar = $(this).data('lebar');
      let tinggi = $(this).data('tinggi');
      let packing = $(this).data('packing');

      if (packing == 1) {
        $('#e_packing').prop('checked', true);
      }
      $('#e_nama_barang').val(id_barang).trigger('change');
      $('#id_detail').val(id_detail);
      $('#e_jumlah').val(jumlah);
      $('#e_berat').val(berat);
      $('#e_panjang').val(panjang);
      $('#e_lebar').val(lebar);
      $('#e_tinggi').val(tinggi);

      $('#editBarangModal').modal('show');
    });

    $('#dt1').on('click', '.btn-hapus', function() {
			let id_detail = $(this).data('id');
			if (confirm("Apakah Anda yakin ingin menghapus barang ini?")) {
				$.ajax({
					url: "<?= base_url(); ?>data/delete_detail_order",
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

  });
</script>


<?= $this->endSection(); ?>
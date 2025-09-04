<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <div class="align-items-center mb-4">
          <h1 class="h3 mb-0 text-gray-800 "><?= $title; ?></h1>
     </div>

     <div class="row mb-3">

          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">
               <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                         <?php foreach (session()->getFlashdata('errors') as $error): ?>
                              <p><?= $error ?></p>
                         <?php endforeach; ?>
                    </div>
               <?php endif; ?>
               <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                         <?= session()->getFlashdata('success') ?>
                    </div>
               <?php endif; ?>
               <div class="card mb-5">
                    <div class="card-header border border-primary">
                         <h5 class="card-title mb-0">Form Tambah Biaya</h5>
                    </div>
                    <div class="card-body border border-primary">
                         <form action="<?= base_url('admin/biaya/'.$biaya['id_layanan']); ?>" method="post">
                              <?= csrf_field(); ?>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="pengirim">Pengirim</label>
                                        <select id="edit-pengirim" name="edit-pengirim" class="form-control mySelect2" required <?= ($biaya['in_order'] > 0) ? 'disabled' : ''; ?>>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($pengirim as $data) : ?>
                                                  <option value="<?= $data['id_pelanggan']; ?>" <?= $data['id_pelanggan'] == $biaya['id_pelanggan'] ? 'selected' : ''; ?>><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <input type="hidden" name="pengirim" id="pengirim" value="<?= $biaya['id_pelanggan']; ?>">
                                   <input type="hidden" name="penerima" id="penerima" value="<?= $biaya['id_penerima']; ?>">
                                   <div class="form-group col-md-6">
                                        <label for="penerima-biaya">Penerima</label>
                                        <select id="penerima-biaya" name="edit-penerima" class="form-control mySelect2" required <?= ($biaya['in_order'] > 0) ? 'disabled' : ''; ?>>
                                             <?php
                                             foreach ($infoPengirim as $pp):
                                             ?>
                                                  <option value="<?= $pp['id_penerima']; ?>" 
                                                  data-id-prov="<?= $pp['provinsi_id']; ?>" 
                                                  data-prov="<?= $pp['provinsi']; ?>" 
                                                  data-id-kab="<?= $pp['kabupaten_id']; ?>"
                                                  data-kab="<?= $pp['kabupaten']; ?>"
                                                  data-id-kec="<?= $pp['kecamatan_id']; ?>" 
                                                  data-kec="<?= $pp['kecamatan']; ?>" <?= ($pp['id_penerima'] == $biaya['id_penerima']) ? 'selected' : ''; ?>>
                                                       <?= $pp['nama_penerima'] ?>
                                                  </option>
                                             <?php
                                             endforeach;
                                             ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-4">
                                        <label for="provinsi">Provinsi</label>
                                        <input type="text" class="form-control" id="pilih-provinsi" value="<?= $infoPenerima[0]['provinsi']; ?>" readonly>
                                        <input type="hidden" class="form-control" id="id-provinsi" name="prov" value="<?= $infoPenerima[0]['provinsi_id']; ?>">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="kabupaten">Kabupaten</label>
                                        <input type="text" class="form-control" id="pilih-kabupaten" value="<?= $infoPenerima[0]['kabupaten']; ?>" readonly>
                                        <input type="hidden" class="form-control" id="id-kabupaten" name="kab" value="<?= $infoPenerima[0]['kabupaten_id']; ?>">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="kecamatan">Kecamatan</label>
                                        <input type="text" class="form-control" id="pilih-kecamatan" value="<?= $infoPenerima[0]['kecamatan']; ?>" readonly>
                                        <input type="hidden" class="form-control" id="id-kecamatan" name="kec" value="<?= $infoPenerima[0]['kecamatan_id']; ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-4">
                                        <label for="layanan">Layanan/Services</label>
                                        <select id="layanan" name="layanan" class="form-control mySelect2" required>
                                             <option value="">Choose...</option>
                                             <?php foreach ($layanan as $data) : ?>
                                                  <option value="<?= $data['id']; ?>" <?= $data['choice_name'] == $biaya['layanan'] ? 'selected' : ''; ?>><?= $data['choice_name']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="minimum">Minimum</label>
                                        <input type="text" class="form-control" id="minimum" name="minimum" value="<?= $biaya['minimum']; ?>">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="harga">Harga / Biaya (1 Kg)</label>
                                        <input type="text" class="form-control number" id="harga" name="harga" value="<?= $biaya['biaya_paket']; ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-4">
                                        <label for="jenis_tagihan">Jenis Tagihan</label>
                                        <select id="jenis_tagihan" name="jenis_tagihan" class="form-control mySelect2" required>
                                             <option value="">Choose...</option>
                                             <option <?= $biaya['bill_type'] == 'reguler' ? 'selected' : ''; ?> value="reguler">Reguler</option>
                                             <option <?= $biaya['bill_type'] == 'flat' ? 'selected' : ''; ?> value="flat">Flat Rate</option>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="leadtime">Leadtime Pengiriman</label>
                                        <input type="text" class="form-control" id="leadtime" name="leadtime" value="<?= $biaya['leadtime']; ?>">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="divider">Divider (Pembagi Kiloan)</label>
                                        <input type="text" class="form-control number" id="divider" name="divider" value="<?= $biaya['divider']; ?>">
                                   </div>
                              </div>
                              <input type="hidden" name="_method" value="PUT">
                              <input type="hidden" name="id_layanan" value="<?= $biaya['id_layanan']; ?>">
                              <a href="<?= base_url('admin/biaya'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left"> Cancel</i></a>
                              <button type="submit" class="btn btn-primary"> <i class="fa fa-save"> Save </i></button>
                         </form>
                    </div>
               </div>
          </div>
     </div>

</div>

<script>
     $(document).ready(function() {
          $('#edit-pengirim').on('change', function() {
               var id = this.value;
               $('#pengirim').val(id);
               $.ajax({
                    url: "<?= base_url('data/get_penerima_by_pengirim/'); ?>" + id,
                    method: "GET",
                    dataType: 'json',
                    success: function(data) {
                         var penerimaSelect = $('#penerima-biaya');
                         penerimaSelect.empty(); // Kosongkan dulu opsi sebelumnya
                         penerimaSelect.append('<option selected value="">Choose...</option>'); // Tambahkan opsi default

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

          $('#penerima-biaya').on('change', function() {
               var id = $("#penerima-biaya").val();
               $('#penerima').val(id);
               var id_provinsi = $(this).find('option:selected').data('id-prov');
               var provinsi = $(this).find('option:selected').data('prov');
               var id_kabupaten = $(this).find('option:selected').data('id-prov');
               var kabupaten = $(this).find('option:selected').data('kab');
               var id_kecamatan = $(this).find('option:selected').data('id-kec');
               var kecamatan = $(this).find('option:selected').data('kec');

               $('#id-provinsi').val(id_provinsi);
               $('#pilih-provinsi').val(provinsi);
               $('#pilih-provinsi').prop('readonly', true);
               $('#id-kabupaten').val(id_kabupaten);
               $('#pilih-kabupaten').val(kabupaten);
               $('#pilih-kabupaten').prop('readonly', true);
               $('#id-kecamatan').val(id_kecamatan);
               $('#pilih-kecamatan').val(kecamatan);
               $('#pilih-kecamatan').prop('readonly', true);

               console.log(id);
               console.log($('#penerima-biaya').val());
               console.log(this.value);
          });
          // $(document).on('change', '#penerima-biaya', function() {
          //      console.log("Penerima dipilih: " + this.value);
          // });

     });
</script>

<?= $this->endSection(); ?>
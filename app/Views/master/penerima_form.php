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
                         <a href="<?= base_url('admin/penerima'); ?>" class="btn btn-warning btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
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

                         <form action="<?= base_url('data/savePenerima'); ?>" method="POST">
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="pelanggan">Pelanggan</label>
                                        <select id="pelanggan" name="pelanggan" class="mySelect2 form-control">
                                             <option value="" <?= set_select('pelanggan', '', true); ?> id="pilih-pelanggan">Choose...</option>
                                             <?php foreach ($pelanggan as $plg) : ?>
                                                  <option <?= (isset($penerima[0]['id_pelanggan']) && $penerima[0]['id_pelanggan'] == $plg['id_pelanggan'] ? ' selected' : '') ?> value="<?= $plg['id_pelanggan']; ?>" <?= set_select('pelanggan', $plg['id_pelanggan']); ?>>
                                                       <?= $plg['nama_pelanggan']; ?>
                                                  </option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <div class="input-group">
                                             <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?= set_value('email', ($penerima[0]['email_penerima'] ?? '')); ?>">
                                        </div>
                                   </div>
                              </div>

                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="penerima">Nama Penerima</label>
                                        <div class="input-group">
                                             <input type="text" class="form-control" id="penerima" name="penerima" placeholder="Nama Penerima" value="<?= set_value('penerima', ($penerima[0]['nama_penerima'] ?? '')); ?>">
                                        </div>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="telp">Telepon Penerima</label>
                                        <input type="text" class="form-control" id="telp" name="telp" value="<?= set_value('telp', ($penerima[0]['telepon_penerima'] ?? '')); ?>">
                                   </div>
                              </div>

                              <div class="form-group">
                                   <label for="alamat">Alamat Penerima</label>
                                   <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= set_value('alamat', ($penerima[0]['alamat_penerima'] ?? '')); ?></textarea>
                              </div>

                              <div class="form-row">
                                   <div class="form-group col-md-3">
                                        <label for="provinsi">Provinsi</label>
                                        <select id="provinsi" name="prov" class="mySelect2 form-control">
                                             <option value="" <?= set_select('prov', '', true); ?> id="pilih-provinsi">Choose...</option>
                                             <?php foreach ($provinsi as $data) : ?>
                                                  <option <?= (isset($penerima[0]['provinsi_id']) && $penerima[0]['provinsi_id'] == $data['id'] ? ' selected' : '') ?> value="<?= $data['id']; ?>" <?= set_select('prov', $data['id']); ?>>
                                                       <?= $data['nama']; ?>
                                                  </option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-3">
                                        <label for="kabupaten">Kabupaten</label>
                                        <select id="kabupaten" name="kab" class="mySelect2 form-control kab">
                                             <option value="" <?= set_select('kab', '', true); ?> id="pilih-kabupaten">Choose...</option>
                                             <?php if (isset($penerima[0]['kabupaten_id'])) {
                                             ?>
                                                  <?php foreach ($kabupaten as $data) : ?>
                                                       <option <?= ($penerima[0]['kabupaten_id'] == $data['id'] ? ' selected' : '') ?> value="<?= $data['id']; ?>" <?= set_select('kab', $data['id']); ?>>
                                                            <?= $data['nama']; ?>
                                                       </option>
                                                  <?php endforeach; ?>
                                             <?php
                                             } ?>
                                        </select>
                                   </div>

                                   <div class="form-group col-md-3">
                                        <label for="kecamatan">Kecamatan</label>
                                        <select id="kecamatan" name="kec" class="mySelect2 form-control kec">
                                             <option value="" <?= set_select('kec', '', true); ?> id="pilih-kecamatan">Choose...</option>
                                             <?php if (isset($penerima[0]['kecamatan_id'])) {
                                             ?>
                                                  <?php foreach ($kecamatan as $data) : ?>
                                                       <option <?= ($penerima[0]['kecamatan_id'] == $data['id'] ? ' selected' : '') ?> value="<?= $data['id']; ?>" <?= set_select('kec', $data['id']); ?>>
                                                            <?= $data['nama']; ?>
                                                       </option>
                                                  <?php endforeach; ?>
                                             <?php
                                             } ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-3">
                                        <label for="desa">Desa</label>
                                        <select id="desa" name="des" class="mySelect2 form-control des">
                                             <option value="" <?= set_select('des', '', true); ?> id="pilih-desa">Choose...</option>
                                             <?php if (isset($penerima[0]['desa_id'])) {
                                             ?>
                                                  <?php foreach ($desa as $data) : ?>
                                                       <option <?= ($penerima[0]['desa_id'] == $data['id'] ? ' selected' : '') ?> value="<?= $data['id']; ?>" <?= set_select('des', $data['id']); ?>>
                                                            <?= $data['nama']; ?>
                                                       </option>
                                                  <?php endforeach; ?>
                                             <?php
                                             } ?>
                                        </select>
                                   </div>
                                   <input type="hidden" name="id" value="<?= $penerima[0]['id_penerima'] ?? '' ?>">
                              </div>
                              <button type="submit" class="btn btn-primary" name="t_penerima">
                                   Tambah Data
                              </button>
                         </form>

                    </div>
               </div>
          </div>
     </div>
</div>

<script>
     $('#provinsi').on('change', function() {
          var id = $('#provinsi').val();
          $.ajax({
               url: "<?= base_url(); ?>data/getKabupaten/" + id,
               method: "GET",
               dataType: 'json',
               success: function(response) {
                    $('#kabupaten').empty();
                    $('#kabupaten').append('<option selected id="pilih-kabupaten">Choose...</option>');
                    $.each(response, function(index, kabupaten) {
                         $('#kabupaten').append('<option value="' + kabupaten.id + '">' + kabupaten.nama + '</option>');
                    });
               },
               error: function() {
                    alert('Terjadi kesalahan');
               }
          });
     });
     $('#kabupaten').on('change', function() {
          var id = $('#kabupaten').val();
          $.ajax({
               url: "<?= base_url(); ?>data/getKecamatan/" + id,
               method: "GET",
               dataType: 'json',
               success: function(response) {
                    $('#kecamatan').empty();
                    $('#kecamatan').append('<option selected id="pilih-kecamatan">Choose...</option>');
                    $.each(response, function(index, kecamatan) {
                         $('#kecamatan').append('<option value="' + kecamatan.id + '">' + kecamatan.nama + '</option>');
                    });
               },
               error: function() {
                    alert('Terjadi kesalahan');
               }
          });
     });
     $('#kecamatan').on('change', function() {
          var id = $('#kecamatan').val();
          $.ajax({
               url: "<?= base_url(); ?>data/getDesa/" + id,
               method: "GET",
               dataType: 'json',
               success: function(response) {
                    $('#desa').empty();
                    $('#desa').append('<option selected id="pilih-desa">Choose...</option>');
                    $.each(response, function(index, desa) {
                         $('#desa').append('<option value="' + desa.id + '">' + desa.nama + '</option>');
                    });
               },
               error: function() {
                    alert('Terjadi kesalahan');
               }
          });
     });
</script>

<?= $this->endSection(); ?>
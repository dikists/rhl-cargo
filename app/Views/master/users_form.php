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
                         <a href="<?= base_url('admin/admin/users'); ?>" class="btn btn-warning btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
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

                         <form action="<?= base_url('data/saveUsers') ?>" method="post" enctype="multipart/form-data">
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="full_name">Nama Lengkap</label>
                                        <input type="text" name="full_name" class="form-control" id="full_name" value="<?= old('full_name', ($users['full_name'] ?? '')); ?>">
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="telepon">Telepon</label>
                                        <input type="text" name="telepon" class="form-control" id="telepon" value="<?= old('telepon', ($users['telepon'] ?? '')); ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" value="<?= old('email', ($users['email'] ?? '')); ?>">
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control" id="username" value="<?= old('username', ($users['username'] ?? '')); ?>">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" id="password" value="<?= old('password'); ?>">
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="role_id">Role</label>
                                        <select id="role_id" name="role_id" class="mySelect2 form-control role_id">
                                             <option value="" <?= set_select('role_id', '', true); ?> id="pilih-role">Choose...</option>
                                             <?php foreach ($role as $data) : ?>
                                                  <option <?= isset($users) && $users['role_id'] == $data['role_id'] ? 'selected' : ''; ?> value="<?= $data['role_id']; ?>" <?= set_select('role_id', $data['role_id']); ?>>
                                                       <?= $data['role_name']; ?>
                                                  </option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>
                              <div class="form-row align-items-center">
                                   <div class="form-group col-md-8">
                                        <label for="foto">Foto</label>
                                        <input type="file" name="foto" class="form-control-file" id="foto">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <?php 
                                        $img = ($users['foto'] ?? 'default.jpg');
                                        ?>
                                        <img src="<?= base_url('assets/img/users/' . $img . ''); ?>" alt="default foto" width="100px" class="img-thumbnail">
                                   </div>
                              </div>
                              <input type="hidden" id="id" name="id" value="<?= old('id', ($users['id'] ?? '')); ?>">
                              <input type="hidden" id="fotolama" name="fotolama" value="<?= old('id', ($users['foto'] ?? '')); ?>">
                              <button type="submit" class="btn btn-primary">Simpan</button>
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
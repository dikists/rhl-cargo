<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Ubah Password</h1>
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
  </div>

  <!-- Content Row -->
  <div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
      <div class="card border-success shadow h-100 py-2">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
          <h6 class="m-0 font-weight-bold text-black">Ubah Password Anda</h6>
        </div>
        <div class="card-body">
          <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
              <?= session()->getFlashdata('error') ?>
            </div>
          <?php endif; ?>
          <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success">
              <?= session()->getFlashdata('success') ?>
            </div>
          <?php endif; ?>
          <form action="<?= base_url('ubah-password') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
              <label for="current_password">Password Lama</label>
              <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
              <label for="new_password">Password Baru</label>
              <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password">Konfirmasi Password Baru</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Ubah Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

<?= $this->endSection(); ?>
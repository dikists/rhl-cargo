<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<?php
$img = base_url() . 'assets/img/users/' . $user['foto'];
if ($user['foto'] == null) {
    $img = base_url() . 'assets/img/users/default.jpg';
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <!-- Form Ubah Password -->
        <div class="col-md-6">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php elseif (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header bg-warning text-white">Ubah Password</div>
                <div class="card-body">
                    <form action="<?= base_url('admin/profile/update_password') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">

                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
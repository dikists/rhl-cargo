<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <div class="profile-header text-center mt-5">
          <img src="https://via.placeholder.com/150" alt="Profile Image" class="profile-img rounded-circle">
          <h1 class="profile-name mt-3"><?= $user['nama_pelanggan']; ?></h1>
          <p class="profile-description"><?= $user['alamat_pelanggan']; ?></p>
          <p class="profile-description"><?= $user['email']; ?></p>
          <p class="profile-description"><?= $user['telepon_pelanggan']; ?></p>
          <p class="profile-description"><?= $user['kota']; ?></p>
     </div>

</div>
<?= $this->endSection(); ?>
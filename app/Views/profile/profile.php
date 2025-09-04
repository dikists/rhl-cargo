<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<style>
    .profile-img-wrapper {
        width: 200px;
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .upload-btn {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .profile-img-wrapper:hover .upload-btn {
        opacity: 1;
    }

    .upload-btn span {
        z-index: 10;
    }

    .profile-img-wrapper:hover .upload-btn {
        opacity: 1;
    }

    .loading-spinner {
        display: none;
        /* Awalnya hidden */
        z-index: 10;
    }
</style>



<?php
$img = base_url() . 'assets/img/users/' . $user['foto'];
if ($user['foto'] == null) {
    $img = base_url() . 'assets/img/users/default.jpg';
}
?>


<div class="container mt-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">Edit Profil</div>
                <div class="card-body">
                    <form action="<?= base_url('admin/profile/update_profile') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">

                        <div class="form-group">
                            <label>Kode User</label>
                            <input type="text" name="kode_user" class="form-control" value="<?= $user['kode_user'] ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $user['username'] ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" value="<?= $user['full_name'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?= $user['telepon'] ?>">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>">
                        </div>

                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- FORM UPLOAD -->
            <form id="formUploadFoto" enctype="multipart/form-data">
                <div class="container-fluid">
                    <div class="profile-header text-center mt-5">
                        <div class="profile-img-wrapper position-relative d-inline-block">

                            <!-- Preview Gambar -->
                            <img id="previewFoto" src="<?= $img; ?>" alt="Profile Image" class="profile-img rounded-circle" width="200" height="200">

                            <!-- Tombol Upload -->
                            <label for="uploadFoto" class="upload-btn">
                                <span>Upload Foto</span>
                            </label>

                            <!-- Spinner -->
                            <div id="loadingSpinner"
                                class="position-absolute align-items-center justify-content-center w-100 h-100"
                                style="top: 0; left: 0; background: rgba(0, 0, 0, 0.4); border-radius: 50%; display: none; z-index: 10;">
                                <div class="spinner-border text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Input File -->
                        <input type="file" name="foto" id="uploadFoto" accept="image/*" style="display: none;">
                        <input type="text" name="fotolama" id="fotolama" value="<?= $user['foto']; ?>" style="display: none;">

                        <h1 class="profile-name mt-3"><?= $user['full_name']; ?></h1>
                        <p class="profile-description"><?= $user['email']; ?></p>
                        <p class="profile-description"><?= $user['telepon']; ?></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('uploadFoto').addEventListener('change', function() {
        const file = this.files[0];
        const fotoLama = document.getElementById('fotolama').value;
        const preview = document.getElementById('previewFoto');
        const spinner = document.getElementById('loadingSpinner');

        if (file) {
            // Tampilkan preview dulu
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Tampilkan spinner
            spinner.style.display = 'flex';

            // Siapkan FormData
            const formData = new FormData();
            formData.append('foto', file);
            formData.append('fotolama', fotoLama);

            // Kirim via AJAX
            fetch('/admin/profile/upload_foto', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json()) // atau .text() tergantung respon server
                .then(data => {
                    // Sukses
                    console.log(data);
                    spinner.style.display = 'none';

                    // Optional: update src jika server ganti URL foto
                    // preview.src = data.new_image_url;

                })
                .catch(error => {
                    console.error('Upload gagal:', error);
                    spinner.style.display = 'none';
                    alert("Upload gagal.");
                });
        }
    });
</script>

<?= $this->endSection(); ?>
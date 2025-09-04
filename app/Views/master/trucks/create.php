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
                    <?php
                    $role = session()->get('role');
                    if ($role !== 'PIC RELASI') {
                    ?>
                        <a href="<?= base_url('admin/truck/create'); ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah data
                        </a>
                    <?php
                    }
                    ?>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/truck/store') ?>" method="post">
                        <div class="form-group">
                            <label>Plat Nomor</label>
                            <input type="text" name="plate_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis</label>
                            <input type="text" name="type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Merk</label>
                            <input type="text" name="brand" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="<?= base_url('admin/truck') ?>" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
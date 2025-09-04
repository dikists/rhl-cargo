<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    </div>
    <form action="<?= base_url('admin/settings/save_role'); ?>" method="POST">
        <div class="row mb-3">
            <!-- Area Chart -->
            <div class="col-xl-4 col-lg-4">
                <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                        <h6 class="m-0 font-weight-bold text-black">Nama Role</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <input class="form-control" name="role_name" type="text" placeholder="Nama Role Baru" required>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8">
                <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                        <h6 class="m-0 font-weight-bold text-black">Akses Menu</h6>
                        <div class="float-right">
                            <a href="<?= base_url('admin/settings/user_role'); ?>" class="btn btn-warning btn-sm text-dark">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama Menu</th>
                                        <th scope="col">Link</th>
                                        <th scope="col" class="text-center">Otoritas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= $menu ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<?= $this->endSection(); ?>
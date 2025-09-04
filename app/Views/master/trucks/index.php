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
                   
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Plat Nomor</th>
                                    <th>Jenis</th>
                                    <th>Merk</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($trucks as $index => $truck): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($truck['plate_number']) ?></td>
                                        <td><?= esc($truck['type']) ?></td>
                                        <td><?= esc($truck['brand']) ?></td>
                                        <td><?= esc($truck['year']) ?></td>
                                        <td><?= esc($truck['status']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/truck/edit/' . $truck['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="<?= base_url('admin/truck/delete/' . $truck['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
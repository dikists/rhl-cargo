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
                    <a href="<?= base_url('admin/settings/new_role'); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah data
                    </a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($roles as $ur) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $ur['role_name']; ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/settings/edit_role/') . $ur['role_id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusRole<?= $ur['role_id']; ?>">
                                                Hapus
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" style="background-color: rgba(0, 0, 0, 0.5);" id="hapusRole<?= $ur['role_id']; ?>" tabindex="-1" aria-labelledby="hapusRole<?= $ur['role_id']; ?>Label" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="hapusRole<?= $ur['role_id']; ?>Label">Hapus Data Role</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Hapus Data Role ? <?= $ur['role_name']; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                                                            <a href="<?= base_url('admin/settings/delete_role/'); ?><?= $ur['role_id']; ?>" class="btn btn-sm btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
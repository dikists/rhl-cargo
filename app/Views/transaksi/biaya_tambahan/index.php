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
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambahBiaya">
                        <i class="fas fa-plus"></i> Tambah data
                    </button>
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
                                    <th>No</th>
                                    <th>Jenis Biaya</th>
                                    <th>Nominal</th>
                                    <th>Tanggal Input</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($biaya as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($row['jenis_biaya']) ?></td>
                                        <td><?= number_format($row['nominal'], 0, ',', '.') ?></td>
                                        <td><?= esc(date('d-m-Y', strtotime($row['tanggal_input']))) ?></td>
                                        <td><?= esc($row['keterangan']) ?></td>
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

<!-- Modal -->
<form action="<?= base_url('admin/'); ?>biaya_tambahan/save" method="post">
    <div class="modal fade" id="tambahBiaya" tabindex="-1" aria-labelledby="tambahBiayaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahBiayaLabel">Modal Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jenis_biaya">Jenis Biaya</label>
                        <input type="text" class="form-control" id="jenis_biaya" name="jenis_biaya" required>
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="text" class="form-control number" id="nominal" name="nominal" required>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection(); ?>
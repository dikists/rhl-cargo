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
                    <a href="<?= base_url('admin/hutang/create'); ?>" class="btn btn-success btn-sm">
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
                    <form action="<?= base_url('admin/hutang/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">No. Invoice</label>
                            <input type="text" class="form-control" name="invoice_number" id="invoice_number" required>
                        </div>

                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control mySelect2" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['vendor_id'] ?>"><?= esc($supplier['vendor_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Tanggal Invoice</label>
                            <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" name="invoice_date" id="invoice_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Hutang</label>
                            <input type="number" class="form-control" name="total_amount" id="total_amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Catatan</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('admin/hutang') ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
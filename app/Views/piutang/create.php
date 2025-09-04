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
                    <a href="<?= base_url('admin/piutang'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('receivables/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="invoice_number" class="form-label">No. Invoice</label>
                            <input type="text" class="form-control" name="invoice_number" id="invoice_number" required>
                        </div>

                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Pelanggan</label>
                            <select name="customer_id" id="customer_id" class="form-control mySelect2" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= $customer['id_pelanggan'] ?>"><?= esc($customer['nama_pelanggan']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Tanggal Invoice</label>
                            <input type="date" class="form-control" name="invoice_date" id="invoice_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Total Piutang</label>
                            <input type="number" class="form-control" name="total_amount" id="total_amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('receivables') ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
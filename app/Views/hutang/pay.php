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
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#tambahBiaya">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('admin/hutang/pay/' . $payable['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="form-label">Supplier</label>
                                <input type="text" class="form-control" value="<?= esc($payable['supplier_name']) ?>" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">No Invoice</label>
                                <input type="text" class="form-control" value="<?= esc($payable['invoice_number']) ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="form-label">Total Hutang</label>
                                <input type="text" class="form-control" value="<?= number_format($payable['total_amount'], 0, ',', '.') ?>" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">Sudah Dibayar</label>
                                <input type="text" class="form-control" value="<?= number_format($payable['paid_amount'], 0, ',', '.') ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" id="payment_date" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="giro">Giro</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Bayar</label>
                            <input type="number" name="amount" id="amount" value="<?= $payable['total_amount'] - $payable['paid_amount'] ?>" class="form-control" required max="<?= $payable['total_amount'] - $payable['paid_amount'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
                        <a href="<?= base_url('admin/hutang') ?>" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
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
                    <h6 class="m-0 font-weight-bold text-black">
                        <?= $title; ?>
                    </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">

                    <h3>Laporan Hutang</h3>
                    <table class="table table-bordered table-sm dataTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Supplier</th>
                                <th>No Invoice</th>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th>Total</th>
                                <th>Terbayar</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payables as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($row['supplier_name']) ?></td>
                                    <td><?= esc($row['invoice_number']) ?></td>
                                    <td><?= esc($row['invoice_date']) ?></td>
                                    <td><?= esc($row['due_date']) ?></td>
                                    <td><?= formatRupiah($row['total_amount']) ?></td>
                                    <td><?= formatRupiah($row['paid_amount']) ?></td>
                                    <td><?= formatRupiah($row['total_amount'] - $row['paid_amount']) ?></td>
                                    <td><?= esc($row['status']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                    <br>

                    <h3>Laporan Piutang</h3>
                    <table class="table table-bordered table-sm dataTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer</th>
                                <th>No Invoice</th>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th>Total</th>
                                <th>Terbayar</th>
                                <th>Sisa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($receivables as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($row['customer_name']) ?></td>
                                    <td><?= esc($row['invoice_number']) ?></td>
                                    <td><?= esc($row['invoice_date']) ?></td>
                                    <td><?= esc($row['due_date']) ?></td>
                                    <td><?= formatRupiah($row['total_amount']) ?></td>
                                    <td><?= formatRupiah($row['paid_amount']) ?></td>
                                    <td><?= formatRupiah($row['total_amount'] - $row['paid_amount']) ?></td>
                                    <td><?= esc($row['status']) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
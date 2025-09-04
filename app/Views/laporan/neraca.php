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

                    <h3>Aktiva (Assets)</h3>
                    <table class="table table-bordered table-striped table-sm" style="width:100%;">
                        <?php $total_asset = 0; ?>
                        <?php foreach ($neraca['assets'] as $item): ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td style="text-align:right"><?= formatRupiah($item['saldo']) ?></td>
                            </tr>
                            <?php $total_asset += $item['saldo']; ?>
                        <?php endforeach; ?>
                        <tr>
                            <th>Total Aktiva</th>
                            <th style="text-align:right"><?= formatRupiah($total_asset) ?></th>
                        </tr>
                    </table>

                    <h3>Kewajiban (Liabilities)</h3>
                    <table class="table table-bordered table-striped table-sm" style="width:100%;">
                        <?php $total_liabilities = 0; ?>
                        <?php foreach ($neraca['liabilities'] as $item): ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td style="text-align:right"><?= formatRupiah($item['saldo']) ?></td>
                            </tr>
                            <?php $total_liabilities += $item['saldo']; ?>
                        <?php endforeach; ?>
                    </table>

                    <h3>Ekuitas (Equity)</h3>
                    <table class="table table-bordered table-striped table-sm" style="width:100%;">
                        <?php $total_equity = 0; ?>
                        <?php foreach ($neraca['equity'] as $item): ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td style="text-align:right"><?= formatRupiah($item['saldo']) ?></td>
                            </tr>
                            <?php $total_equity += $item['saldo']; ?>
                        <?php endforeach; ?>
                    </table>

                    <hr>
                    <h3>Total Liabilitas + Ekuitas: <?= formatRupiah($total_liabilities + $total_equity) ?></h3>

                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
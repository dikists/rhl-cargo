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
                    <h2>Laporan Laba Rugi</h2>

                    <h3>Pendapatan</h3>
                    <table class="table table-bordered table-sm dataTable" style="width:100%;">
                        <?php foreach ($pendapatan as $item): ?>
                            <tr>
                                <td><?= esc($item['name']) ?></td>
                                <td style="text-align: right"><?= formatRupiah($item['saldo']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Total Pendapatan</th>
                            <th style="text-align: right"><?= formatRupiah($total_pendapatan) ?></th>
                        </tr>
                    </table>

                    <h3>Pengeluaran</h3>
                    <table class="table table-bordered table-sm dataTable" style="width:100%;">
                        <?php foreach ($beban as $item): ?>
                            <tr>
                                <td><?= esc($item['name']) ?></td>
                                <td style="text-align: right"><?= formatRupiah($item['saldo']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Total Pengeluaran</th>
                            <th style="text-align: right"><?= formatRupiah($total_beban) ?></th>
                        </tr>
                    </table>

                    <hr>

                    <h3>Laba Bersih Usaha sebelum dipotong Pajak:
                        <span style="float: right"><?= formatRupiah($laba_bersih) ?></span>
                    </h3>
                    <h3>Potong PPh Final Psl 4:
                        <span style="float: right"><?= formatRupiah($laba_bersih * 0.005) ?></span>
                    </h3>
                    <h3>Laba Bersih Usaha Sesudah Pajak:
                        <span style="float: right"><?= formatRupiah($laba_bersih - ($laba_bersih * 0.005)) ?></span>
                    </h3>

                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
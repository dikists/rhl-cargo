<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center mb-4 text-center">
        <h1 class="h3 mb-0 text-gray-800 text-center"><?= $title; ?></h1>
        <?php
        if (empty($orders)) {
            echo "<h5 class='text-center mt-5'>Tidak Ada Data</h5>";
            echo "<h5 class='text-center mt-5'>Silahkan Tambahkan Data</h5>";
            echo "<a href='" . base_url('admin/surat_jalan') . "' class='btn btn-primary mt-5'>Kembali</a>";
        }
        ?>
    </div>

    <div class="row mb-3">

        <!-- Area Chart -->`
        <div class="col-xl-12 col-lg-12">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php foreach ($orders as $order): ?>
                <div class="card mb-5">
                    <div class="card-header border border-primary">
                        <h3 class="card-title mb-0 text-center"><u><?= $order['no_order']; ?></u> <br><br></h5>
                            <h5 class="card-title mb-0 float-left"><?= $order['nama_pelanggan']; ?> <br> <?= $order['alamat_pelanggan']; ?></h5>
                            <h5 class="card-title mb-0 float-right"><?= $order['nama_penerima']; ?> <br> <?= $order['alamat_penerima']; ?></h5>
                    </div>
                    <div class="card-body border border-primary">
                        <form action="<?= base_url('data/saveSuratJalan'); ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="id_order" value="<?= $order['id_order']; ?>">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="no_surat_jalan">No. Surat Jalan</label>
                                    <input type="text" class="form-control" id="no_surat_jalan" name="no_surat_jalan" value="<?= old('no_surat_jalan', $no_sj); ?>" placeholder="Masukkan No. Surat Jalan" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="driver">Driver</label>
                                    <select name="driver" class="form-control mySelect2">
                                        <option value="">Pilih Driver</option>
                                        <?php foreach ($drivers as $driver): ?>
                                            <option value="<?= $driver['id']; ?>"><?= $driver['full_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nopol">Nopol</label>
                                    <select name="nopol" class="form-control mySelect2">
                                        <option value="">Pilih Nopol</option>
                                        <?php foreach ($nopol as $n): ?>
                                            <option value="<?= $n['id']; ?>"><?= $n['plate_number']; ?> - <?= $n['type']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                        <div class="table-responsive mt-3">
                            <table id="dt1" class="table table-bordered table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">P</th>
                                        <th scope="col">L</th>
                                        <th scope="col">T</th>
                                        <th scope="col">Volume</th>
                                        <th scope="col">Berat</th>
                                        <th scope="col">Berat Volume</th>
                                        <th scope="col">Total Hitung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    $details = json_decode($order['detail_order']);
                                    if (count($details) == 0) {
                                        echo "<tr><td colspan='10'>Data tidak valid.</td></tr>";
                                    }
                                    $totalJumlah = $totalKg = $totalVolume = $totalBeratVolume = $totalHitungKeseluruhan = 0;

                                    if ($details) {
                                        foreach ($details as $detail) {
                                            if (is_object($detail) && isset($detail->volume)) {
                                                $beratVolume = ceil($detail->volume / $detail->divider);
                                                $totalHitung = ($detail->berat > $beratVolume) ? $detail->berat : $beratVolume;
                                                $totalJumlah += $detail->jumlah;
                                                $totalVolume += $detail->volume;
                                                $totalKg += $detail->berat;
                                                $totalBeratVolume += $beratVolume;
                                                $totalHitungKeseluruhan += $totalHitung * $detail->jumlah;

                                    ?>
                                                <tr>
                                                    <th scope="row"><?= $no++; ?></th>
                                                    <td><?= $detail->produk; ?></td>
                                                    <td><?= $detail->jumlah; ?></td>
                                                    <td><?= $detail->panjang; ?></td>
                                                    <td><?= $detail->lebar; ?></td>
                                                    <td><?= $detail->tinggi; ?></td>
                                                    <td><?= $detail->volume; ?> CM3</td>
                                                    <td><?= $detail->berat; ?> KG</td>
                                                    <td><?= $beratVolume; ?> KG</td>
                                                    <td><?= ($totalHitung * $detail->jumlah); ?> KG</td>
                                                </tr>
                                    <?php }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <?php
                                    $minimum = isset($details[0]->minimum) ? $details[0]->minimum : 0;
                                    if ($totalHitungKeseluruhan < $minimum) {
                                        $totalHitungKeseluruhan = $minimum . ' (Charge Minimum)';
                                    }
                                    ?>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th><?= $totalJumlah; ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><?= $totalVolume; ?> CM3</th>
                                        <th><?= $totalKg; ?> KG</th>
                                        <th><?= $totalBeratVolume; ?> KG</th>
                                        <th><?= $totalHitungKeseluruhan; ?> KG</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>
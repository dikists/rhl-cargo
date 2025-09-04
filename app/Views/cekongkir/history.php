<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    </div>

    <div class="row mb-3">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4 border-success">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                    <h6 class="m-0 font-weight-bold text-black">Riwayat Cek Ongkir</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">

                    <table class="table table-bordered table-sm" id="dt1">
                        <thead class="table-light">
                            <tr>
                                <th>Asal</th>
                                <th>Tujuan</th>
                                <th>Kurir</th>
                                <th>Layanan</th>
                                <th>Biaya</th>
                                <th>Estimasi</th>
                                <th>Berat</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($histories as $row): ?>
                                <tr>
                                    <td><?= esc($row['origin_city_name']) ?></td>
                                    <td><?= esc($row['destination_city_name']) ?></td>
                                    <td><?= strtoupper($row['courier']) ?></td>
                                    <td><?= esc($row['service']) ?></td>
                                    <td>Rp <?= number_format($row['cost'], 0, ',', '.') ?></td>
                                    <td><?= esc($row['etd']) ?> hari</td>
                                    <td><?= esc($row['weight']) ?> kg</td>
                                    <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <a href="<?= base_url('admin/cekongkir') ?>" class="btn btn-secondary">Kembali</a>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var tabel = $('#dt1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'
            ],
            processing: true, // Mengaktifkan tampilan "Processing..."
            language: {
                processing: "Loading...", // Teks yang akan muncul saat loading
                loadingRecords: "Loading data..."
            }
        });
    });
</script>
<?= $this->endSection(); ?>
<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="m-4 text-gray-800"><?= $title; ?> No Resi : <?= $pengiriman['no_surat_jalan']; ?></h1>
    <h2 class="m-4 text-gray-800"><?= $pengiriman['shipper']; ?> - <?= $pengiriman['consignee']; ?></h2>
    <h2 class="m-4 text-gray-800">Tanggal : <?= date('Y-m-d', strtotime($pengiriman['tanggal_order'])); ?></h2>

    <div class="row mb-3">
        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4 border-success">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                    <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>
                    <div class="float-right">
                        <a href="<?= base_url('admin/pengiriman'); ?>" class="btn btn-warning btn-sm text-dark">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addExtraCost">
                            <i class="fas fa-plus"></i> Tambah
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($extraCharge)) : ?>
                        <p class="text-muted">Tidak ada biaya tambahan.</p>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Biaya</th>
                                        <th>Tipe Tagih</th>
                                        <th>Keterangan</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    $total = 0;
                                    foreach ($extraCharge as $row) :
                                        $total += $row['charge_value'];
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= esc($row['jenis_biaya']); ?></td>
                                            <td><?= esc($row['tipe_tagih']); ?></td>
                                            <td><?= esc($row['keterangan']); ?></td>
                                            <td class="text-right">Rp <?= number_format($row['charge_value'], 0, ',', '.'); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteExtraCost<?= $row['id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <form action="<?= base_url('admin/pengiriman/delete_extra_cost/' . $row['id'].'/'. $row['pengiriman_id']); ?>" method="post">
                                            <div class="modal fade" id="deleteExtraCost<?= $row['id']; ?>" tabindex="-1" aria-labelledby="deleteExtraCost<?= $row['id']; ?>Label" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteExtraCost<?= $row['id']; ?>Label">Tambah Biaya Tambahan</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah anda yakin ingin menghapus biaya tambahan ini?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Hapus</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                    <?php endforeach; ?>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th class="text-right text-success">Rp <?= number_format($total, 0, ',', '.'); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<form action="<?= base_url('admin/pengiriman/add_extra_cost'); ?>" method="post">
    <div class="modal fade" id="addExtraCost" tabindex="-1" aria-labelledby="addExtraCostLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExtraCostLabel">Tambah Biaya Tambahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="biaya_id">Nama Biaya</label>
                        <select name="biaya_id" id="biaya_id" class="form-control mySelect2">
                            <option value="">Pilih Biaya</option>
                            <?php foreach ($biayaTambahan as $data) : ?>
                                <option value="<?= $data['id_biaya']; ?>"
                                    data-tipe_tagih="<?= $data['tipe_tagih']; ?>" data-keterangan="<?= $data['keterangan']; ?>" data-nilai="<?= $data['nominal']; ?>">
                                    <?= $data['jenis_biaya']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipe_tagih">Tipe Tagih</label>
                        <input type="text" class="form-control" id="tipe_tagih" name="tipe_tagih" placeholder="Tipe Tagih" readonly>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Nilai" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_pengiriman" value="<?= $pengiriman['id_pengiriman']; ?>">
                    <input type="hidden" name="berat" value="<?= $pengirimanSummary[0]['berat']; ?>">
                    <input type="hidden" name="biaya_kirim" value="<?= $pengirimanSummary[0]['biaya_kirim']; ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#biaya_id').on('change', function() {
            var tipeTagih = $(this).find(':selected').data('tipe_tagih');
            var keterangan = $(this).find(':selected').data('keterangan');
            var nilai = $(this).find(':selected').data('nilai');
            nilai = nilai.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            $('#tipe_tagih').val(tipeTagih);
            $('#keterangan').val(keterangan);
            $('#nominal').val(nilai);
        });
    });
</script>

<?= $this->endSection(); ?>
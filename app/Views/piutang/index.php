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
                    <a href="<?= base_url('admin/piutang/create'); ?>" class="btn btn-success btn-sm">
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

                    <div class="row my-2">
                        <div class="col-sm-4">
                            <label for="date_start">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker_id" id="date_start" name="date_start" value="<?= date('01-m-Y'); ?>">
                        </div>
                        <div class="col-sm-4">
                            <label for="date_end">Tanggal Akhir</label>
                            <input type="text" class="form-control datepicker_id" id="date_end" name="date_end" value="<?= date('d-m-Y'); ?>">
                        </div>
                        <div class="col-sm-4">
                            <label for="pelanggan">Pelanggan</label>
                            <select name="pelanggan" class="form-control mySelect2" id="pelanggan">
                                <option selected value="">Semua</option>
                                <?php foreach ($pelanggan as $data) : ?>
                                    <option value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control mySelect2" required>
                                <option selected value="">Semua</option>
                                <option value="unpaid">Belum Lunas</option>
                                <option value="paid">Lunas</option>
                            </select>
                        </div>
                    </div>
                    <center>
                        <button type="button" class="btn btn-primary my-2 btn-sm" id="filterButton"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</button>
                    </center>

                    <div class="row mt-3 justify-content-center" id="total-summary">
                        <div class="col-sm-6">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr class="table-light">
                                            <th class="text-center">Total Piutang</th>
                                            <th class="text-center">Dibayar</th>
                                            <th class="text-center">Sisa</th>
                                        </tr>
                                        <tr class="table-light">
                                            <th id="total_amount" class="text-center"></th>
                                            <th id="total_paid" class="text-center"></th>
                                            <th id="total_sisa" class="text-center"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-striped" id="hutangTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Terbayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#hutangTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('data/piutang/datatables') ?>',
                type: 'POST',
                data: function(d) {
                    d.date_start = convertDate($('#date_start').val());
                    d.date_end = convertDate($('#date_end').val());
                    d.pelanggan = $('#pelanggan').val();
                    d.status = $('#status').val();
                },
                dataSrc: function(json) {
                    // Tampilkan total
                    $('#total_amount').text(formatRupiah(json.total_amount));
                    $('#total_paid').text(formatRupiah(json.total_paid));
                    $('#total_sisa').text(formatRupiah(json.total_sisa));
                    return json.data;
                }
            }
        });

        $('#filterButton').click(function() {
            table.ajax.reload();
        });

    });
</script>

<?= $this->endSection(); ?>
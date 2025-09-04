<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 "><?= $title; ?></h1>
    </div>

    <div class="row mb-3">

        <!-- Area Chart -->
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
            <div class="card mb-5">
                <div class="card-header border d-flex flex-row border-primary align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Daftar Uang Jalan</h5>
                    <a href="<?= base_url('admin/uang_jalan/add'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Data</a>
                </div>
                <div class="card-body border border-primary">
                    <div class="row my-2">
                        <div class="col-sm-4">
                            <label for="date_start">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker_id" id="date_start" name="date_start" value="<?= date('01-m-Y'); ?>">
                        </div>
                        <div class="col-sm-4">
                            <label for="date_end">Tanggal Akhir</label>
                            <input type="text" class="form-control datepicker_id" id="date_end" name="date_end" value="<?= date('d-m-Y'); ?>">
                        </div>
                        <?php if (session()->get('role') == 'DRIVER'): ?>
                            <div class="col-sm-4">
                                <label for="drivers">Driver</label>
                                <input type="text" class="form-control" id="drivers_name" name="drivers_name" value="<?= ucwords(session()->get('username')); ?>" readonly="readonly">
                                <input type="text" class="form-control" id="drivers" name="drivers" value="<?= session()->get('id'); ?>" hidden="hidden">
                            </div>
                        <?php else: ?>
                            <div class="col-sm-4">
                                <label for="drivers">Driver</label>
                                <select id="drivers" name="drivers" class="form-control mySelect2" required>
                                    <option selected value="">Semua</option>
                                    <?php foreach ($drivers as $data) : ?>
                                        <option value="<?= $data['id']; ?>"><?= ucwords($data['username']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control mySelect2" required>
                                <option selected value="">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <center>
                        <button type="button" class="btn btn-primary my-2 btn-sm" id="filterButton"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</button>
                    </center>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-striped font-small text-center" id="uangJalanTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Reference</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Driver</th>
                                    <th class="text-center">Tujuan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
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
        var table = $('#uangJalanTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('data/uangjalan/datatables') ?>',
                type: 'POST',
                data: function(d) {
                    d.date_start = convertDate($('#date_start').val());
                    d.date_end = convertDate($('#date_end').val());
                    d.drivers = $('#drivers').val();
                    d.status = $('#status').val();
                }
            }
        });

        $('#filterButton').click(function() {
            table.ajax.reload();
        });
    });
</script>

<?= $this->endSection(); ?>
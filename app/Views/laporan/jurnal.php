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

                    <h3>Laporan Jurnal</h3>

                    <div class="row my-2">
                        <div class="col-sm-6">
                            <label for="date_start">Tanggal Awal</label>
                            <input type="text" class="form-control datepicker_id" id="date_start" name="date_start" value="<?= date('01-m-Y'); ?>">
                        </div>
                        <div class="col-sm-6">
                            <label for="date_end">Tanggal Akhir</label>
                            <input type="text" class="form-control datepicker_id" id="date_end" name="date_end" value="<?= date('d-m-Y'); ?>">
                        </div>
                    </div>
                    <center>
                        <button type="button" class="btn btn-primary my-2 btn-sm" id="filterButton"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</button>
                        <button type="button" class="btn btn-danger my-2 btn-sm" id="pdfButton"><i class="fa fa-file-pdf" aria-hidden="true"></i> Export PDF</button>
                    </center>

                    <table class="table table-bordered table-sm table-striped" id="jurnalTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Akun</th>
                                <th>Referensi</th>
                                <th>Keterangan</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#jurnalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('laporan/jurnal/datatables') ?>',
                type: 'POST',
                data: function(d) {
                    d.date_start = convertDate($('#date_start').val());
                    d.date_end = convertDate($('#date_end').val());
                }
            },
            pageLength: 20
        });

        $('#filterButton').click(function() {
            table.ajax.reload();
        });
        $('#pdfButton').click(function() {
            let date_start = convertDate($('#date_start').val());
            let date_end = convertDate($('#date_end').val());
            let query = $.param({
                date_start: date_start,
                date_end: date_end
            });
            window.open("<?= base_url(); ?>laporan_jurnal/export_pdf?" + query, '_blank');
        });

    });
</script>

<?= $this->endSection(); ?>
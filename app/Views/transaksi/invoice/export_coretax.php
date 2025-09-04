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
                    <div class="float-right">
                        <button type="button" class="btn btn-success btn-sm m-1 btn-tambah-export-data" data-toggle="modal" data-target="#modal_export">
                            <i class="fas fa-plus"></i> Tambah Export Data
                        </button>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mt-3 dataTable text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Invoice Numbers</th>
                                    <th class="text-center">Export</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($exportInvoice)): ?>
                                    <?php foreach ($exportInvoice as $i => $row): ?>
                                        <tr>
                                            <td><?= $i + 1; ?></td>
                                            <td width="10%"><?= date('d-m-Y', strtotime($row['receipt_export_date'])); ?></td>
                                            <td><?= $row['invoice_numbers']; ?></td>
                                            <td width="10%">
                                                <a href="javascript:void(0)" onclick="export_to_xml(<?= $row['receipt_export_id']; ?>)" class="btn btn-primary btn-sm btn-detail-export-data mt-1">
                                                    <i class="fas fa-download"></i> XML
                                                </a>
                                            </td>
                                            <td width="10%">
                                                <a href="javascript:void(0)" onclick="detail_export(<?= $row['receipt_export_id']; ?>)" class="btn btn-primary btn-sm btn-detail-export-data mt-1">
                                                    <i class="fas fa-info-circle"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<form action="<?= base_url() ?>admin/invoice/export" method="post" id="form_export">
    <div class="modal fade" id="modal_export" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="max-height: 500px; overflow-y: auto;" class="row" id="list_export_invoices_container">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-sm">
                                <thead style="position: sticky; top: 0; z-index: 1; background-color: #f8f9fa;">
                                    <tr class="text-center bg-light">
                                        <th width="25%">
                                            <div class="checkbox px-1">
                                                <input type="checkbox" class="check-all mr-2" data-type="#modal_export"> Nomor Invoice
                                            </div>
                                        </th>
                                        <th width="55%">Bill To</th>
                                        <th width="20%">Tgl. Invoice</th>
                                    </tr>
                                    <tr class="text-center bg-light">
                                        <th width="25%">
                                            <input type="text" class="form-control form-control-sm" id="filter_invoice" onkeyup="filter_modal_export()">
                                        </th>
                                        <th width="55%">
                                            <input type="text" class="form-control form-control-sm" id="filter_billto" onkeyup="filter_modal_export()">
                                        </th>
                                        <th width="20%">
                                            <input type="text" class="form-control form-control-sm" id="filter_date" onkeyup="filter_modal_export()">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="list_exported_invoice">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> loading data ...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Tutup</button>
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-save mr-1"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Modal detail  -->
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th width="80%">Nomor Invoice</th>
                                    <th width="20%">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="list_receipt">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on("click", ".btn-tambah-export-data", function() {
            filter_modal_export();
        });

    });

    function action_receipt_cancel(receipt_id, receipt_export_id) {
        // Tampilkan konfirmasi menggunakan window.confirm bawaan atau SweetAlert kalau dipakai
        if (confirm('Apakah Anda yakin untuk membatalkan invoice ini?')) {
            const url = '<?= base_url() ?>admin/invoice/action_receipt_cancel';
            const data = {
                receipt_id: receipt_id,
                receipt_export_id: receipt_export_id
            };

            $.post(url, data, function(response) {
                const res = JSON.parse(response); // parse jika responsenya masih dalam bentuk string
                alert(res.message);
                detail_export(receipt_export_id);
                document.location.reload();
            });
        }
    }

    function export_to_xml(receipt_export_id) {
        window.open('<?= base_url() ?>admin/invoice/export_to_xml/' + receipt_export_id, '_blank');
    }


    function detail_export(receipt_export_id) {
        // clear list_receipt
        $('#list_receipt').html('');

        const url = '<?= base_url() ?>admin/invoice/get_receipt_tax';
        const data = {
            receipt_export_id: receipt_export_id
        };

        $.get(url, data, function(data) {
            const response = JSON.parse(data);
            const row_data = response.rows;
            $.each(row_data, function(index, value) {
                $('#list_receipt').append(`
                    <tr>
                        <td>` + value.invoice_number + `</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="action_receipt_cancel(` + value.id + `, ` + receipt_export_id + `)">
                                <i class="fa fa-info-circle mr-1"></i> Batal
                            </button>
                        </td>
                    </tr>
                `);
            });
            $('#modal_detail').modal('show');
        });
    }

    function filter_modal_export() {

        const filter_invoice = $('#filter_invoice').val();
        const filter_billto = $('#filter_billto').val();
        const filter_date = $('#filter_date').val();
        let output = '';

        /* loading animation */
        output = `<tr>
                        <td align="center" colspan="3"><i class="fa fa-spinner fa-spin"></i> loading data ...</td>
                    </tr>`;

        $.ajax({
            url: "<?= base_url(); ?>data/getInvoice",
            type: "GET",
            dataType: 'json',
            data: {
                'invoice_number': filter_invoice,
                'billto_name': filter_billto,
                'invoice_date': filter_date,
                'type': 'export_coretax'
            },
            success: function(respond) {
                output = '';

                $.each(respond.rows, function(index, value) {
                    output += `<tr>
                                    <td>
                                        <div class="form-group m-0">
                                            <label class="font-weight-bold" for="id_` + value.id + `"> 
                                                <input type="checkbox" id="id_` + value.id + `" class="list_invoice" name="id[]" value="` + value.id + `">
                                                ` + value.invoice_number + ` 
                                            </label>
                                        </div>
                                    </td>
                                    <td>` + value.nama_pelanggan + `</td>
                                    <td>` + moment(value.issue_date).format('DD-MM-YYYY') + `</td>
                                </tr>`;
                });

                if (respond.rows.length == 0) {
                    output = `<tr>
                                    <td align="center" colspan="3">Tidak ada data</td>
                                </tr>`;
                }

                $("#list_exported_invoice").html(output);
            },
            error: function() {
                /* alert('Error found!'); */
            }
        });
    }

    $(document).on("change", ".check-all", function() {
        const check_el = $('#list_exported_invoice').find('input[type="checkbox"]');
        const main_checked_state = $(this).prop('checked');
        /* each */
        check_el.each(function() {
            $(this).prop('checked', main_checked_state ? true : false);
        });
    });

    function setMaxHeight() {
        var maxHeight = window.innerHeight * 0.65;
        document.querySelector('#list_export_invoices_container').style.maxHeight = maxHeight + 'px';
    }

    window.addEventListener('resize', setMaxHeight);
    window.addEventListener('load', setMaxHeight);
</script>


<?= $this->endSection(); ?>
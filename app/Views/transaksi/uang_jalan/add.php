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
                    <h5 class="card-title mb-0">Tambah Uang Jalan</h5>
                    <a href="<?= base_url('admin/uang_jalan'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left"></i> Kembali </a>
                </div>
                <div class="card-body border border-primary">
                    <form method="post" action="<?= base_url('admin/uang_jalan/save'); ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control datepicker" autocomplete="off" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                            <?php if (session()->get('role') != 'DRIVER'): ?>
                                <div class="form-group col-md-6">
                                    <label>Driver</label>
                                    <select name="user_id" id="user_id" class="form-control mySelect2" required>
                                        <option value="">Pilih Driver</option>
                                        <?php foreach ($drivers as $data): ?>
                                            <option value="<?= $data['id']; ?>" data-username="<?= ucwords($data['username']); ?>"><?= ucwords($data['username']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="form-group col-md-6">
                                <input type="hidden" name="username" id="username">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tujuan</label>
                                <input type="text" name="tujuan" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Jumlah</label>
                                <input type="text" name="jumlah" class="form-control number" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#user_id').on('change', function() {
            var username = $(this).find('option:selected').data('username');
            $('#username').val(username);
        });
        $('#pengirim').on('change', function() {
            var id = this.value;
            $.ajax({
                url: "<?= base_url('data/get_penerima_by_pengirim/'); ?>" + id,
                method: "GET",
                dataType: 'json',
                success: function(data) {
                    var penerimaSelect = $('#penerima-biaya');
                    penerimaSelect.empty(); // Kosongkan dulu opsi sebelumnya
                    penerimaSelect.append('<option selected value="">Choose...</option>'); // Tambahkan opsi default

                    // Looping data hasil AJAX dan tambahkan ke dalam select
                    $.each(data, function(index, item) {
                        penerimaSelect.append(`<option value="` + item.id_penerima + `" 
                              data-id-prov="` + item.provinsi_id + `"
                              data-prov="` + item.provinsi + `" 
                              data-id-kab="` + item.kabupaten_id + `"
                              data-kab="` + item.kabupaten + `" 
                              data-id-kec="` + item.kecamatan_id + `"
                              data-kec="` + item.kecamatan + `"
                              >` + item.nama_penerima + `</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        });

        $('#penerima-biaya').on('change', function() {
            var id = $("#penerima-biaya").val();
            var id_provinsi = $(this).find('option:selected').data('id-prov');
            var provinsi = $(this).find('option:selected').data('prov');
            var id_kabupaten = $(this).find('option:selected').data('id-prov');
            var kabupaten = $(this).find('option:selected').data('kab');
            var id_kecamatan = $(this).find('option:selected').data('id-kec');
            var kecamatan = $(this).find('option:selected').data('kec');

            $('#id-provinsi').val(id_provinsi);
            $('#pilih-provinsi').val(provinsi);
            $('#pilih-provinsi').prop('readonly', true);
            $('#id-kabupaten').val(id_kabupaten);
            $('#pilih-kabupaten').val(kabupaten);
            $('#pilih-kabupaten').prop('readonly', true);
            $('#id-kecamatan').val(id_kecamatan);
            $('#pilih-kecamatan').val(kecamatan);
            $('#pilih-kecamatan').prop('readonly', true);

            console.log(id);
            console.log($('#penerima-biaya').val());
            console.log(this.value);
        });
        // $(document).on('change', '#penerima-biaya', function() {
        //      console.log("Penerima dipilih: " + this.value);
        // });

    });
</script>

<?= $this->endSection(); ?>
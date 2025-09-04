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
                    <h6 class="m-0 font-weight-bold text-black">Cek Ongkir (RajaOngkir API)</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">

                    <form id="form-cekongkir" class="row g-3">
                        <div class="col-md-6">
                            <label>Provinsi Asal</label>
                            <select id="provinsi_asal" class="form-control mySelect2"></select>
                        </div>
                        <div class="col-md-6">
                            <label>Kota Asal</label>
                            <select id="kota_asal" name="origin" class="form-control mySelect2"></select>
                            <input type="text" id="kota_asal_name" name="kota_asal_name" value="" hidden>
                        </div>
                        <div class="col-md-6">
                            <label>Provinsi Tujuan</label>
                            <select id="provinsi_tujuan" class="form-control mySelect2"></select>
                        </div>
                        <div class="col-md-6">
                            <label>Kota Tujuan</label>
                            <select id="kota_tujuan" name="destination" class="form-control mySelect2"></select>
                            <input type="text" id="kota_tujuan_name" name="kota_tujuan_name" value="" hidden>
                        </div>
                        <div class="col-md-4">
                            <label>Berat (gram)</label>
                            <input type="number" name="weight" class="form-control" value="1000" min="1000" step="100" required>
                        </div>
                        <div class="col-md-4">
                            <label>Kurir</label>
                            <select name="courier" class="form-control">
                                <option value="jne">JNE</option>
                                <option value="pos">POS</option>
                                <option value="tiki">TIKI</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Cek Ongkir</button>
                        </div>
                    </form>
                    <div class="text-center mt-3" id="loading" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                        <p class="mt-2">Sedang memproses...</p>
                    </div>
                    <hr>
                    <div id="hasil" class="mt-3"></div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Load provinsi
    function loadProvinces(selector) {
        fetch('/cekongkir/provinces')
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Pilih Provinsi</option>';
                data.data.forEach(p => {
                    options += `<option value="${p.id}">${p.name}</option>`;
                });
                document.getElementById(selector).innerHTML = options;
            });
    }

    loadProvinces('provinsi_asal');
    loadProvinces('provinsi_tujuan');

    $('#provinsi_asal').on('change', function() {
        let id = this.value;
        fetch('/cekongkir/cities/' + id)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Pilih Kota</option>';
                data.data.forEach(k => {
                    options += `<option value="${k.id}">${k.name}</option>`;
                });
                document.getElementById('kota_asal').innerHTML = options;
            });
    });

    $('#kota_asal').on('change', function() {
        var selectedText = $('#kota_asal option:selected').text(); // Ambil teks dari option yang dipilih
        $('#kota_asal_name').val(selectedText); // Masukkan ke input text
    });

    $('#provinsi_tujuan').on('change', function() {
        let id = this.value;
        fetch('/cekongkir/cities/' + id)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Pilih Kota</option>';
                data.data.forEach(k => {
                    options += `<option value="${k.id}">${k.name}</option>`;
                });
                document.getElementById('kota_tujuan').innerHTML = options;
            });
    });

    $('#kota_tujuan').on('change', function() {
        var selectedText = $('#kota_tujuan option:selected').text(); // Ambil teks dari option yang dipilih
        $('#kota_tujuan_name').val(selectedText); // Masukkan ke input text
    });


    document.getElementById('form-cekongkir').addEventListener('submit', function(e) {
        e.preventDefault();

        const loading = document.getElementById('loading');
        const resultDiv = document.getElementById('hasil');
        let formData = new FormData(this);
        let result = '';
        let result_new = '';

        // Tampilkan loading
        loading.style.display = 'block';
        resultDiv.innerHTML = ''; // bersihkan hasil sebelumnya

        fetch('/cekongkir/cost', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                let result = '';
                let result_new = '';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(service => {
                        let biaya = service.cost;
                        let etd = service.etd;
                        let layanan = service.service;

                        // Contoh mapping ONS/REG/ECO ke UDARA/DARAT/LAUT (jika perlu)
                        if (layanan.includes('ONS')) {
                            biaya = biaya * 78 / 100;
                            layanan = 'UDARA';
                        } else if (layanan.includes('Reg')) {
                            biaya = biaya * 52 / 100;
                            layanan = 'DARAT';
                        } else if (layanan.includes('REG')) {
                            biaya = biaya * 52 / 100;
                            layanan = 'DARAT';
                        } else if (layanan.includes('JTR')) {
                            biaya = biaya * 26 / 100;
                            layanan = 'LAUT';
                        } else if (layanan.includes('Eco')) {
                            biaya = biaya * 26 / 100;
                            layanan = 'LAUT';
                        }

                        result_new += `
                                    <div class="card mb-2 bg-success text-white">
                                        <div class="card-body">
                                            <h5>${layanan}</h5>
                                            <p>${service.name}</p>
                                            <strong>Biaya: Rp ${biaya.toLocaleString('id-ID')}</strong><br>
                                            Estimasi: ${etd}
                                        </div>
                                    </div>
                                `;

                        result += `
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h5>${service.service}</h5>
                                        <p>${service.name}</p>
                                        <strong>Biaya: Rp ${service.cost.toLocaleString('id-ID')}</strong><br>
                                        Estimasi: ${etd}
                                    </div>
                                </div>
                            `;
                    });

                    resultDiv.innerHTML += result_new;
                    resultDiv.innerHTML += result;

                } else {
                    resultDiv.innerHTML = '<div class="alert alert-warning">Tidak ada data ongkir.</div>';
                }
            })
            .catch(err => {
                loading.style.display = 'none';
                resultDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memproses permintaan.</div>';
                console.error('Error:', err);
            });

    });
</script>

<?= $this->endSection(); ?>
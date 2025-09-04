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
                    <div class="card-header border border-primary">
                         <h5 class="card-title mb-0">Form Tambah Biaya</h5>
                    </div>
                    <div class="card-body border border-primary">
                         <form action="<?= base_url('admin/biaya'); ?>" method="post">
                              <?= csrf_field(); ?>
                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="pengirim">Pengirim</label>
                                        <select id="pengirim" name="pengirim" class="form-control mySelect2" required>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($pengirim as $data) : ?>
                                                  <option value="<?= $data['id_pelanggan']; ?>"><?= $data['nama_pelanggan']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="penerima-biaya">Penerima</label>
                                        <select id="penerima-biaya" name="penerima" class="form-control mySelect2" required>
                                             <option selected value="">Pilih Pengirim Dulu...</option>
                                        </select>
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-4">
                                        <label for="provinsi">Provinsi</label>
                                        <input type="text" class="form-control" id="pilih-provinsi">
                                        <input type="hidden" class="form-control" id="id-provinsi" name="prov">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="kabupaten">Kabupaten</label>
                                        <input type="text" class="form-control" id="pilih-kabupaten">
                                        <input type="hidden" class="form-control" id="id-kabupaten" name="kab">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="kecamatan">Kecamatan</label>
                                        <input type="text" class="form-control" id="pilih-kecamatan">
                                        <input type="hidden" class="form-control" id="id-kecamatan" name="kec">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-4">
                                        <label for="layanan">Layanan/Services</label>
                                        <select id="layanan" name="layanan" class="form-control mySelect2">
                                             <option selected>Choose...</option>
                                             <?php foreach ($layanan as $data) : ?>
                                                  <option value="<?= $data['id']; ?>"><?= $data['choice_name']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="minimum">Minimum</label>
                                        <input type="text" class="form-control" id="minimum" name="minimum">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="harga">Harga / Biaya (1 Kg)</label>
                                        <input type="text" class="form-control number" id="harga" name="harga">
                                   </div>
                              </div>
                              <div class="form-row">
                                   <div class="form-group col-md-4">
                                        <label for="jenis_tagihan">Jenis Tagihan</label>
                                        <select id="jenis_tagihan" name="jenis_tagihan" class="form-control mySelect2" required>
                                             <option selected>Choose...</option>
                                             <option value="reguler">Reguler</option>
                                             <option value="flat">Flat Rate</option>
                                        </select>
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="leadtime">Leadtime Pengiriman</label>
                                        <input type="text" class="form-control" id="leadtime" name="leadtime">
                                   </div>
                                   <div class="form-group col-md-4">
                                        <label for="divider">Divider (Pembagi Kiloan)</label>
                                        <input type="text" class="form-control number" id="divider" name="divider">
                                   </div>
                              </div>
                              <a href="<?= base_url('admin/biaya'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left"> Cancel</i></a>
                              <button type="submit" class="btn btn-primary"> <i class="fa fa-save"> Save </i></button>
                         </form>
                    </div>
               </div>
          </div>
     </div>

</div>

<script>
     $(document).ready(function() {
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
<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><?= $title; ?> : <?= $pelanggan['nama_pelanggan']; ?></h1>
     </div>

     <div class="row mb-3">

          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">

               <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                         <?= session()->getFlashdata('success'); ?>
                    </div>
               <?php endif; ?>

               <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                         <?php foreach (session()->getFlashdata('errors') as $error): ?>
                              <p><?= $error ?></p>
                         <?php endforeach; ?>
                    </div>
               <?php endif; ?>

               <div class="alert alert-info">
                    <ul class="mb-0">
                         <li>Exclude lapor ( ppn di tanggung relasi terbit faktur) dan di print out invoice tampilkan ppn</li>
                         <li>Include lapor (harga sudah termasuk ppn terbit faktur) dan di print out invoice tampilkan ppn</li>
                         <li>Exclude tidak lapor (ppn di tanggung relasi dan tidak terbit faktur) dan di print out invoice tampilkan ppn</li>
                         <li>Include tidak lapor (harga sudah termasuk ppn tidak terbit faktur) dan di print out invoice tampilkan ppn</li>
                         <li>Gungung (pajak di tanggung perusahaan tidak terbit faktur) dan di print out invoice ppn tidak ditampilkan</li>
                    </ul>
               </div>
               <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                         <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>
                         <div class="float-right">
                              <a href="<?= base_url('admin/pelanggan'); ?>" class="btn btn-warning btn-sm">
                                   <i class="fas fa-arrow-left"></i> Kembali
                              </a>
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="addPPN" data-target="#formPelangganPPN">
                                   <i class="fas fa-plus"></i> Tambah Data
                              </button>
                         </div>
                    </div>
                    <!-- Modal -->
                    <form action="<?= base_url('relation-tax'); ?>" method="post" name="addForm" onsubmit="return validateAdd()">

                         <div class="modal fade" id="formPelangganPPN" aria-labelledby="formPelangganPPNLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg">
                                   <div class="modal-content">
                                        <div class="modal-header">
                                             <h5 class="modal-title" id="formPelangganPPNLabel">Tambah Data PPN Pelanggan</h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                             </button>
                                        </div>
                                        <div class="modal-body">
                                             <div class="row">
                                                  <div class="col-lg-12">
                                                       <div class="form-group">
                                                            <label> Kategori Penagihan* </label>
                                                            <select class="form-control mySelect2" id="choice_id" name="choice_id" required="">
                                                                 <option value="">Pilih</option>
                                                                 <?php
                                                                 foreach ($category as $c) {
                                                                      echo '<option value="' . $c['id'] . '">' . $c['choice_name'] . '</option>';
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </div>
                                                       <div class="form-group">
                                                            <label> Jenis PPN* </label>
                                                            <select class="form-control mySelect2" id="ppn_type" name="ppn_type" required="">
                                                                 <option value="">Pilih</option>
                                                                 <option value="Y">Include Lapor</option>
                                                                 <option value="N">Exclude Lapor</option>
                                                                 <option value="S">Include Tidak Lapor</option>
                                                                 <option value="X">Exclude Tidak Lapor</option>
                                                                 <option value="I">Digungung</option>
                                                            </select>
                                                       </div>
                                                       <div class="form-group">
                                                            <label> Jumlah PPN (%)* </label>
                                                            <select class="form-control mySelect2" id="tax_value" name="tax_value" required="" onchange="option_gunggung()">
                                                                 <option value="">Pilih</option>
                                                                 <?php
                                                                 for ($i_tax = 0; $i_tax < count($tax_values); $i_tax++) {
                                                                      echo '<option value="' . $tax_values[$i_tax] . '">' . $tax_values[$i_tax] . '%</option>';
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </div>
                                                       <div class="form-group" id="input_gunggung" style="display:none">
                                                            <label> Gunggung </label>
                                                            <select class="form-control" id="tax_gunggung" name="tax_gunggung" required="">
                                                                 <option value="N">Tidak</option>
                                                                 <option value="Y">Ya</option>
                                                            </select>
                                                       </div>
                                                       <div class="form-group">
                                                            <label> Tanggal Berlaku* </label>
                                                            <input type="text" class="form-control datepicker" id="date_start" name="date_start" required="" autocomplete="off">
                                                       </div>
                                                       <div class="form-group">
                                                            <label> Tanggal Berakhir </label>
                                                            <input type="text" class="form-control datepicker" id="date_end" name="date_end" autocomplete="off">
                                                       </div>
                                                       <div class="form-group">
                                                            <label> Keterangan </label>
                                                            <textarea class="form-control" rows="3" id="description" name="description"></textarea>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="modal-footer">
                                             <input type="hidden" name="relation_id" id="relation_id" value="<?= $pelanggan['id_pelanggan']; ?>">
                                             <input type="hidden" name="id" id="id">
                                             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                             <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </form>
                    <!-- Card Body -->
                    <div class="card-body">
                         <div class="table-responsive">
                              <table class="table table-bordered table-sm dataTable" style="width: 100%" id="datatables">
                                   <thead>
                                        <tr>
                                             <th>No</th>
                                             <th width="15%" align="center"> Kategori Penagihan </th>
                                             <th width="10%" align="center"> Jenis PPN </th>
                                             <th width="10%" align="center"> PPN (%) </th>
                                             <th width="10%" align="center"> Tanggal Berlaku </th>
                                             <th width="10%" align="center"> Tanggal Berakhir </th>
                                             <th width="10%" align="center"> Waktu Buat </th>
                                             <th width="25%" align="center"> Keterangan </th>
                                             <th width="10%" align="center">Opsi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($rtax as $row) : ?>
                                             <?php
                                             if ($row['rtax_ppn_type'] == "Y") $ppn_type = 'Include Lapor';
                                             elseif ($row['rtax_ppn_type'] == "N") $ppn_type = 'Exclude Lapor';
                                             elseif ($row['rtax_ppn_type'] == "S") $ppn_type = 'Include Tidak Lapor';
                                             elseif ($row['rtax_ppn_type'] == "X") $ppn_type = 'Exclude Tidak Lapor';
                                             elseif ($row['rtax_ppn_type'] == "I") $ppn_type = 'Digungung';
                                             ?>
                                             <tr>
                                                  <td><?= $no++; ?></td>
                                                  <td><?= $row['choice_name']; ?></td>
                                                  <td><?= $ppn_type; ?></td>
                                                  <td><?= $row['rtax_value']; ?></td>
                                                  <td><?= date_format(date_create($row['rtax_date_start']), 'd-m-Y'); ?></td>
                                                  <td><?= $row['rtax_date_end'] != null ? date_format(date_create($row['rtax_date_end']), 'd-m-Y') : null ?></td>
                                                  <td><?= $row['rtax_create_at']; ?></td>
                                                  <td><?= $row['rtax_desc']; ?></td>
                                                  <td>
                                                       <a href="#" class="delete-btn" data-id="<?= $row['rtax_id']; ?>"><i class="fa fa-trash text-danger fa-2x m-1" title="Hapus"></i></a>
                                                       <a href="#" data-toggle="modal" data-target="#modal_ppn_edit" data-id="<?= $row['rtax_id']; ?>"
                                                            data-id="<?= $row['rtax_id']; ?>"
                                                            data-choice_id="<?= $row['id']; ?>"
                                                            data-choice_name="<?= $row['choice_name']; ?>"
                                                            data-ppn_type="<?= $row['rtax_ppn_type']; ?>"
                                                            data-tax_value="<?= $row['rtax_value']; ?>"
                                                            data-date_start="<?= $row['rtax_date_start']; ?>"
                                                            data-date_end="<?= $row['rtax_date_end']; ?>"
                                                            data-description="<?= $row['rtax_desc']; ?>"><i class="fa fa-edit fa-2x m-1" title="Edit"></i></a>
                                                  </td>
                                             </tr>
                                        <?php endforeach; ?>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<!-- modal ppn edit -->
<div class="modal fade" id="modal_ppn_edit" role="dialog" aria-labelledby="ppn_edit" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="ppn_edit">Edit PPN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <form method="POST" autocomplete="OFF" action="<?= base_url('relation-tax/' . $pelanggan['id_pelanggan']); ?>" name="editForm" onsubmit="return validateEdit()">

                    <div class="modal-body">
                         <input type="hidden" name="_method" value="PUT">
                         <input type="hidden" name="relation_id" value="<?= $pelanggan['id_pelanggan']; ?>">
                         <input type="hidden" id="rtax_id" name="rtax_id">

                         <div class="row">
                              <div class="col-lg-12">
                                   <div class="form-group">
                                        <label> Kategori Penagihan* </label>
                                        <select class="form-control mySelect2" id="choice_id_edit" name="choice_id" required="">
                                             <option value="">Pilih</option>
                                             <?php
                                             foreach ($category as $c) {
                                                  echo '<option value="' . $c['id'] . '">' . $c['choice_name'] . '</option>';
                                             }
                                             ?>
                                        </select>
                                   </div>
                                   <div class="form-group">
                                        <label> Jenis PPN* </label>
                                        <select class="form-control mySelect2" id="ppn_type_edit" name="ppn_type" required="">
                                             <option value="">Pilih</option>
                                             <option value="Y">Include Lapor</option>
                                             <option value="N">Exclude Lapor</option>
                                             <option value="S">Include Tidak Lapor</option>
                                             <option value="X">Exclude Tidak Lapor</option>
                                             <option value="I">Digungung</option>
                                        </select>
                                   </div>
                                   <div class="form-group">
                                        <label> Jumlah PPN (%)* </label>
                                        <select class="form-control mySelect2" id="tax_value_edit" name="tax_value" required="" onchange="option_gunggung(`_edit`)">
                                             <option value="">Pilih</option>
                                             <?php
                                             for ($i_tax = 0; $i_tax < count($tax_values); $i_tax++) {
                                                  echo '<option value="' . $tax_values[$i_tax] . '">' . $tax_values[$i_tax] . '%</option>';
                                             }
                                             ?>
                                        </select>
                                   </div>
                                   <div class="form-group" id="input_gunggung_edit" style="display:none">
                                        <label> Gunggung </label>
                                        <select class="form-control" id="tax_gunggung_edit" name="tax_gunggung" required="">
                                             <option value="N">Tidak</option>
                                             <option value="Y">Ya</option>
                                        </select>
                                   </div>
                                   <div class="form-group">
                                        <label> Tanggal Berlaku* </label>
                                        <input type="text" class="form-control datepicker" id="date_start_edit" name="date_start" required="">
                                   </div>
                                   <div class="form-group">
                                        <label> Tanggal Berakhir </label>
                                        <input type="text" class="form-control datepicker" id="date_end_edit" name="date_end">
                                   </div>
                                   <div class="form-group">
                                        <label> Keterangan </label>
                                        <textarea class="form-control" rows="3" id="description_edit" name="description"></textarea>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                         <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
               </form>
          </div>
     </div>
</div>

<script>
     function option_gunggung(form_type = '') {
          var tax_value = $('#tax_value' + form_type).val();

          if (tax_value == '0') {
               $('#input_gunggung' + form_type).show();
          } else {
               $('#input_gunggung' + form_type).hide();
               $('#tax_gunggung_edit').val('N').change();
          }

     }

     function validateEdit() {

          var startDate = document.forms['editForm']['date_start'].value;
          var endDate = document.forms['editForm']['date_end'].value;

          if (endDate != '') {
               startDate = startDate.split('-');
               endDate = endDate.split('-');
               var sd = new Date(startDate[2], startDate[1] - 1, startDate[0]);
               var ed = new Date(endDate[2], endDate[1] - 1, endDate[0]);
               if (sd > ed) {
                    alert("Salah pilih Tanggal Berakhir!");
                    return false;
               }
          }
     }

     function validateAdd() {

          var startDate = document.forms['addForm']['date_start'].value;
          var endDate = document.forms['addForm']['date_end'].value;

          if (endDate != '') {
               startDate = startDate.split('-');
               endDate = endDate.split('-');
               var sd = new Date(startDate[2], startDate[1] - 1, startDate[0]);
               var ed = new Date(endDate[2], endDate[1] - 1, endDate[0]);
               if (sd > ed) {
                    alert("Salah pilih Tanggal Berakhir!");
                    return false;
               }
          }
     }

     $(document).on("click", ".delete-btn", function() {
          let id = $(this).data("id");

          if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
               $.ajax({
                    url: "<?= base_url('relation-tax/'); ?>" + id,
                    type: "DELETE",
                    success: function(response) {
                         alert("Data berhasil dihapus!");
                         location.reload();
                    },
                    error: function() {
                         alert("Gagal menghapus data.");
                    }
               });
          }
     });

     $('#modal_ppn_edit').on('show.bs.modal', function(event) {
          var button = $(event.relatedTarget);
          var rtax_id = button.data('id');
          var rtax_choice_id = button.data('choice_id');
          var rtax_value = button.data('tax_value');
          var rtax_ppn_type = button.data('ppn_type');
          var rtax_gunggung = button.data('tax_gunggung');
          var rtax_date_start = button.data('date_start');
          var rtax_date_end = button.data('date_end');
          var rtax_desc = button.data('description');

          var modal = $(this)
          modal.find('#rtax_id').val(rtax_id);
          modal.find('#choice_id_edit').val(rtax_choice_id).change();
          modal.find('#ppn_type_edit').val(rtax_ppn_type).change();
          modal.find('#tax_value_edit').val(rtax_value).change();
          modal.find('#tax_gunggung_edit').val(rtax_gunggung).change();
          modal.find('#date_start_edit').val(rtax_date_start);
          modal.find('#date_end_edit').val(rtax_date_end);
          modal.find('#description_edit').val(rtax_desc);

          option_gunggung(`_edit`);
     })
</script>


<?= $this->endSection(); ?>
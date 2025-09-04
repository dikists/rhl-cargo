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

               <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                         <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>
                         <div class="float-right">
                              <a href="<?= base_url('admin/pelanggan'); ?>" class="btn btn-warning btn-sm">
                                   <i class="fas fa-arrow-left"></i> Kembali
                              </a>
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="addPPN" data-target="#formPelangganPPH">
                                   <i class="fas fa-plus"></i> Tambah Data
                              </button>
                         </div>
                    </div>
                    <!-- Modal -->
                    <form action="<?= base_url('relation-tax-pph'); ?>" method="post" name="addForm">
                         <div class="modal fade" id="formPelangganPPH" aria-labelledby="formPelangganPPHLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                   <div class="modal-content">
                                        <div class="modal-header">
                                             <h5 class="modal-title" id="formPelangganPPHLabel">Tambah Data PPH Pelanggan</h5>
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                             </button>
                                        </div>
                                        <div class="modal-body">
                                             <div class="row">
                                                  <div class="col-lg-6 px-2 form-group">
                                                       <label>Tanggal Berlaku Awal*</label>
                                                       <input type="text" class="form-control datepicker" name="start_date" id="start-date" autocomplete="off">
                                                  </div>
                                                  <div class="col-lg-6 px-2 form-group">
                                                       <label>Tanggal Berlaku Akhir</label>
                                                       <input type="text" class="form-control datepicker" name="end_date" id="end-date" autocomplete="off">
                                                  </div>
                                                  <div class="col-lg-12 px-2 form-group">
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
                                                  <div class="col-lg-12 px-2 form-group">
                                                       <label> Jumlah PPH (%)* </label>
                                                       <input class="form-control number" name="pph" id="pph" autocomplete="off" />
                                                  </div>
                                                  <div class="col-lg-12 px-2 form-group">
                                                       <label> Keterangan </label>
                                                       <textarea class="form-control" name="description" id="description" autocomplete="off"></textarea>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="modal-footer">
                                             <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="<?= $pelanggan['id_pelanggan']; ?>">
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
                                             <th width="5%" align="center">No</th>
                                             <th width="12%" align="center">Kategori Penagihan</th>
                                             <th width="10%" align="center">Jumlah PPH(%)</th>
                                             <th width="12%" align="center">Tanggal Berlaku Awal</th>
                                             <th width="12%" align="center">Tanggal Berlaku Akhir</th>
                                             <th width="15%" align="center">Keterangan</th>
                                             <th width="15%" align="center">Option</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($tax_pph as $row) : ?>
                                             <tr>
                                                  <td><?= $no++; ?></td>
                                                  <td><?= $row['choice_name']; ?></td>
                                                  <td><?= $row['percent']; ?></td>
                                                  <td><?= date_format(date_create($row['date_start']), 'd-m-Y'); ?></td>
                                                  <td><?= $row['date_end'] != null ? date_format(date_create($row['date_end']), 'd-m-Y') : null ?></td>
                                                  <td><?= $row['description']; ?></td>
                                                  <td>
                                                       <a href="#" class="delete-btn" data-id="<?= $row['id']; ?>"><i class="fa fa-trash text-danger fa-2x m-1" title="Hapus"></i></a>
                                                       <a href="#" data-toggle="modal" data-target="#modal_pph_edit"
                                                            data-id="<?= $row['id']; ?>"
                                                            data-choice_id="<?= $row['choice_id']; ?>"
                                                            data-choice_name="<?= $row['choice_name']; ?>"
                                                            data-percent="<?= $row['percent']; ?>"
                                                            data-date_start="<?= $row['date_start']; ?>"
                                                            data-date_end="<?= $row['date_end']; ?>"
                                                            data-description="<?= $row['description']; ?>"><i class="fa fa-edit fa-2x m-1" title="Edit"></i></a>
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

<!-- Modal Edit -->
<form action="<?= base_url('relation-tax-pph/'.$pelanggan['id_pelanggan']); ?>" method="post" name="addForm">
     <div class="modal fade" id="modal_pph_edit" aria-labelledby="modal_pph_editLabel" aria-hidden="true">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title" id="modal_pph_editLabel">Edit Data PPH Pelanggan</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                         </button>
                    </div>
                    <div class="modal-body">
                         <div class="row">
                              <div class="col-lg-6 px-2 form-group">
                                   <label>Tanggal Berlaku Awal*</label>
                                   <input type="text" class="form-control datepicker" name="start_date" id="date_start_edit" autocomplete="off">
                              </div>
                              <div class="col-lg-6 px-2 form-group">
                                   <label>Tanggal Berlaku Akhir</label>
                                   <input type="text" class="form-control datepicker" name="end_date" id="date_end_edit" autocomplete="off">
                              </div>
                              <div class="col-lg-12 px-2 form-group">
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
                              <div class="col-lg-12 px-2 form-group">
                                   <label> Jumlah PPH (%)* </label>
                                   <input class="form-control number" name="pph" id="pph_edit" autocomplete="off" />
                              </div>
                              <div class="col-lg-12 px-2 form-group">
                                   <label> Keterangan </label>
                                   <textarea class="form-control" name="description" id="description_edit" autocomplete="off"></textarea>
                              </div>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <input type="hidden" name="_method" value="PUT">
                         <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="<?= $pelanggan['id_pelanggan']; ?>">
                         <input type="hidden" name="id" id="rtax_id">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-primary">Save</button>
                    </div>
               </div>
          </div>
     </div>
</form>


<script>
     $(document).on("click", ".delete-btn", function() {
          let id = $(this).data("id");
          console.log(id);

          if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
               $.ajax({
                    url: "<?= base_url('relation-tax-pph/'); ?>" + id,
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

     $('#modal_pph_edit').on('show.bs.modal', function(event) {
          var button = $(event.relatedTarget);
          var rtax_id = button.data('id');
          var choice_id = button.data('choice_id');
          var percent = button.data('percent');
          var date_start = button.data('date_start');
          var date_end = button.data('date_end');
          var description = button.data('description');

          var modal = $(this)
          modal.find('#rtax_id').val(rtax_id);
          modal.find('#choice_id_edit').val(choice_id).change();
          modal.find('#pph_edit').val(percent);
          modal.find('#date_start_edit').val(date_start);
          modal.find('#date_end_edit').val(date_end);
          modal.find('#description_edit').val(description);
     })
</script>


<?= $this->endSection(); ?>
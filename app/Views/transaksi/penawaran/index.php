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
                         <a href="<?= base_url('admin/offers/add'); ?>" class="btn btn-success btn-sm">
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
                         <div class="table-responsive">
                              <table class="table dataTable table-sm table-bordered">
                                   <thead>
                                        <tr>
                                             <th class="text-center">No</th>
                                             <th class="text-center">Nomor Penawaran</th>
                                             <th class="text-center">Nama Pelanggan</th>
                                             <th class="text-center">Tanggal Penawaran</th>
                                             <th class="text-center">Total Harga</th>
                                             <th class="text-center">Status</th>
                                             <th class="text-center">Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($offers as $offer): ?>
                                             <tr>
                                                  <td><?= $no++ ?></td>
                                                  <td><?= $offer['offer_number'] ?></td>
                                                  <td><?= $offer['nama_pelanggan'] ?></td>
                                                  <td><?= $offer['offer_date'] ?></td>
                                                  <td><?= number_format($offer['total_offers'], 0) ?></td>
                                                  <td><?= ($offer['status'] == 'Y') ? 'Belum Generate' : 'Sudah Generate'  ?></td>
                                                  <td>
                                                       <?php if ($offer['status'] == 'Y') { ?>
                                                            <a href="#" class="btn btn-success btn-sm m-1" onclick="modalGenerate(<?= $offer['id'] ?>,'<?= $offer['pelanggan_id'] ?>')">
                                                                 <i class="fas fa-file-invoice"></i> Generate To Invoice
                                                            </a>
                                                            <a href="<?= base_url('admin/offer-details/' . $offer['id']) ?>" class="btn btn-info btn-sm m-1">
                                                                 <i class="fas fa-info"></i> Detail
                                                            </a>
                                                            <a href="<?= base_url('admin/offers/' . $offer['id'] . '/edit') ?>" class="btn btn-warning btn-sm m-1 text-dark">
                                                                 <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <a href="<?= base_url('pdf/offers/' . $offer['id']) ?>" class="btn btn-danger btn-sm m-1" target="_blank">
                                                                 <i class="fas fa-file-pdf"></i> Pdf
                                                            </a>
                                                            <a href="<?= base_url('admin/offers/' . $offer['id'] . '/delete') ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('Apakah anda yakin?')">
                                                                 <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                       <?php } else { ?>
                                                            <a href="<?= base_url('pdf/offers/' . $offer['id']) ?>" class="btn btn-danger btn-sm m-1" target="_blank">
                                                                 <i class="fas fa-file-pdf"></i> Pdf
                                                            </a>
                                                       <?php } ?>
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

<!-- Modal -->
<div class="modal fade" id="modalGenerate" tabindex="-1" aria-labelledby="modalGenerateLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="modalGenerateLabel">Generate To Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
               </div>
               <div class="modal-body">
                    <div class="form-row">
                         <div class="form-group col-md-6">
                              <label for="no_rekening" class="form-label">Nomor Rekening</label>
                              <select name="no_rekening" id="no_rekening" class="form-control mySelect2">
                                   <option selected value="">Choose...</option>
                                   <?php foreach ($rekening as $data) : ?>
                                        <option value="<?= $data['id']; ?>"><?= $data['account_holder_name']; ?> (<?= $data['account_number']; ?>)</option>
                                   <?php endforeach; ?>
                              </select>
                         </div>
                    </div>
                    <div class="form-row">
                         <input type="text" name="offer_id" id="offer_id" hidden>
                         <div class="form-group col-md-6">
                              <label for="rtax" class="form-label">Kategori Penagihan PPN</label>
                              <select name="rtax" id="rtax" class="form-control mySelect2">
                                   <option selected value="">Pilih Billto Dulu</option>
                              </select>
                         </div>
                         <div class="form-group col-md-6">
                              <label for="rtax_pph" class="form-label">Kategori Penagihan PPH</label>
                              <select name="rtax_pph" id="rtax_pph" class="form-control mySelect2">
                                   <option selected value="">Pilih Billto Dulu</option>
                              </select>
                         </div>
                    </div>
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btnGenerate" class="btn btn-primary">Save changes</button>
               </div>
          </div>
     </div>
</div>

<script>
     function modalGenerate(offer_id, id_pelanggan) {
          getTax(id_pelanggan);
          getTaxPph(id_pelanggan);
          $('#offer_id').val(offer_id);
          $('#modalGenerate').modal('show');

     }

     function getTax(id_pelanggan) {
          var pelanggan_id = id_pelanggan;
          $.ajax({
               url: "<?= site_url('data/invoice/getTax'); ?>",
               type: 'post',
               data: {
                    'pelanggan_id': pelanggan_id
               },
               dataType: 'json',
               success: function(response) {
                    var select = $('#rtax');
                    select.empty();

                    if (response.length > 0) {
                         select.append('<option value="">Pilih Kategori</option>');
                         select.append('<option value="">Non PPN</option>');
                         $.each(response, function(index, item) {
                              select.append('<option value="' + item.rtax_id + '">' + item.choice_name + ' (' + item.rtax_value + '%)</option>');
                         });
                    } else {
                         select.append('<option value="">Pilih Kategori</option>');
                         select.append('<option value="">Non PPN</option>');
                    }
               }
          });
     }

     function getTaxPph(id_pelanggan) {
          var pelanggan_id = id_pelanggan;
          $.ajax({
               url: "<?= site_url('data/invoice/getTaxPph'); ?>",
               type: 'post',
               data: {
                    'pelanggan_id': pelanggan_id
               },
               dataType: 'json',
               success: function(response) {
                    var select = $('#rtax_pph');
                    select.empty();

                    if (response.length > 0) {
                         select.append('<option value="">Pilih Kategori</option>');
                         select.append('<option value="">Non PPH</option>');
                         $.each(response, function(index, item) {
                              select.append('<option value="' + item.id + '">' + item.choice_name + ' (' + item.percent + '%)</option>');
                         });
                    } else {
                         select.append('<option value="">Pilih Kategori</option>');
                         select.append('<option value="">Non PPH</option>');
                    }
               }
          });
     }

     $(document).ready(function() {
          $('#btnGenerate').click(function() {
               var offer_id = $('#offer_id').val();
               var rtax = $('#rtax').val();
               var rtax_pph = $('#rtax_pph').val();
               var no_rekening = $('#no_rekening').val();
               var url = "<?= site_url('admin/offers/generateInvoice'); ?>";
               // console.log(offer_id, rtax, rtax_pph);
               $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                         'offer_id': offer_id,
                         'rtax': rtax,
                         'rtax_pph': rtax_pph,
                         'no_rekening': no_rekening
                    },
                    dataType: 'json',
                    success: function(response) {
                         if (response.status) {
                              location.reload();
                         }
                    }
               });
          });
     });
</script>

<?= $this->endSection(); ?>
<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">

     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800"><?= $title; ?> <?= $head['offer_number']; ?></h1>
     </div>

     <div class="row mb-3">

          <!-- Area Chart -->
          <div class="col-xl-12 col-lg-12">
               <div class="card shadow mb-4 border-success">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                         <h6 class="m-0 font-weight-bold text-black"><?= $title; ?></h6>
                         <!-- Button trigger modal -->
                          <div class="float-right">
                               <a href="<?= base_url('admin/offers'); ?>" class="btn btn-warning text-dark"><i class="fas fa-arrow-left"></i> Kembali</a>
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                   <i class="fas fa-plus"></i> Tambah data
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
                              <table class="table dataTable table-sm table-bordered">
                                   <thead>
                                        <tr>
                                             <th>No</th>
                                             <th>Desc</th>
                                             <th>Quantity</th>
                                             <th>Price</th>
                                             <th>Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($details as $d): ?>
                                             <tr>
                                                  <td><?= $no++ ?></td>
                                                  <td><?= $d['item_name'] ?></td>
                                                  <td><?= $d['quantity'] ?></td>
                                                  <td><?= number_format($d['price'], 0, ',', '.'); ?></td>
                                                  <td>
                                                       <a href="<?= base_url('admin/offer-details/' . $d['id'].'/'.$head['id'].'/delete'); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')"><i class="fas fa-trash"></i> Delete</a>
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

<form action="<?= base_url('admin/offer-details/store') ?>" method="post">
     <input type="hidden" name="offer_id" value="<?= $offer_id ?>">
     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title" id="exampleModalLabel">Tambah Detail</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                         </button>
                    </div>
                    <div class="modal-body">
                         <div class="mb-3">
                              <label for="item_name" class="form-label">Deskripsi</label>
                              <input type="text" class="form-control" id="item_name" name="item_name" required>
                         </div>
                         <div class="mb-3">
                              <label for="quantity" class="form-label">Quantity</label>
                              <input type="number" class="form-control" id="quantity" name="quantity" required>
                         </div>
                         <div class="mb-3">
                              <label for="price" class="form-label">Harga</label>
                              <input type="number" class="form-control" id="price" name="price" required>
                         </div>
                         <div class="mb-3">
                              <label for="total" class="form-label">Total</label>
                              <input type="number" class="form-control" id="total" name="total" readonly>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-primary">Save</button>
                    </div>
               </div>
          </div>
     </div>
</form>

<script>
     document.getElementById('quantity').addEventListener('input', calculateTotal);
     document.getElementById('price').addEventListener('input', calculateTotal);

     function calculateTotal() {
          let qty = document.getElementById('quantity').value || 0;
          let price = document.getElementById('price').value || 0;
          document.getElementById('total').value = qty * price;
     }
</script>

<?= $this->endSection(); ?>
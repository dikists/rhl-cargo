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
                         <h5 class="card-title mb-0">Form Add Manifest</h5>
                    </div>
                    <div class="card-body border border-primary">
                         <form action="<?= base_url('data/saveManifest'); ?>" method="post">
                              <?= csrf_field(); ?>

                              <div class="form-row">
                                   <div class="form-group col-md-6">
                                        <label for="date">Tanggal</label>
                                        <input type="text" class="form-control datepicker" id="date" name="date" autocomplete="off" value="<?= date('Y-m-d'); ?>" required>
                                   </div>
                                   <div class="form-group col-md-6">
                                        <label for="vendor">Vendor*</label>
                                        <select id="vendor" name="vendor" class="form-control mySelect2" required>
                                             <option selected value="">Choose...</option>
                                             <?php foreach ($vendors as $data) : ?>
                                                  <option value="<?= $data['vendor_id']; ?>"><?= $data['vendor_name']; ?></option>
                                             <?php endforeach; ?>
                                        </select>
                                   </div>
                              </div>
                              <a href="<?= base_url('admin/manifest'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left"> Cancel</i></a>
                              <button type="submit" class="btn btn-primary"> <i class="fa fa-save"> Save </i></button>
                         </form>
                    </div>
               </div>
          </div>
     </div>

</div>

<?= $this->endSection(); ?>
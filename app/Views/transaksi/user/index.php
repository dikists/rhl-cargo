<?= $this->extend('layout/template_admin'); ?>
<?= $this->section('content'); ?>
<?php
function get_user_relation($id)
{
    $db = \Config\Database::connect();
    $builder = $db->table('relation_user');
    $builder->where('user_id', $id);
    $query = $builder->get();
    $get_data = $query->getResultArray();

    $return['relation_id'] = array();
    foreach ($get_data as $data) {
        $return['relation_id'][] = $data['relation_id'];
    }

    return $return;
}
?>

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

                    <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus"></i> Tambah User
                    </button> -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Fullname</th>
                                    <th>Username</th>
                                    <th>Telepon</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($users as $u) : ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $u['full_name']; ?></td>
                                        <td><?= $u['username']; ?></td>
                                        <td><?= $u['telepon']; ?></td>
                                        <td><?= $u['role_name']; ?></td>
                                        <td>
                                            <!-- <a href="<?= base_url('admin/settings/delete_user/' . $u['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')"><i class="fas fa-trash"></i></a> -->
                                            <?php if ($u['role_name'] == 'PIC RELASI') { ?>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editUser<?= $u['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            <?php } ?>
                                            <!-- Modal -->
                                            <form action="<?= base_url('admin/settings/update_relation_user/' . $u['id']); ?>" method="POST">
                                                <input type="hidden" name="id" value="<?= $u['id']; ?>">
                                                <div class="modal fade" id="editUser<?= $u['id'] ?>" aria-labelledby="editUser<?= $u['id'] ?>Label" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editUser<?= $u['id'] ?>Label">Ubah Data User</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="edit_f_name">Full Name</label>
                                                                    <input type="text" class="form-control" id="edit_f_name_<?= $u['id'] ?>" name="f_name" value="<?= $u['full_name']; ?>" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="edit_username">Username</label>
                                                                    <input type="text" class="form-control" id="edit_username_<?= $u['id'] ?>" name="username" value="<?= $u['username']; ?>" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="edit_relation_id">Relation Akses</label>
                                                                    <select class="form-control select2_multiple" multiple="multiple" style="width: 100%;" id="edit_relation_id_<?= $u['id'] ?>" name="relation_list[]">
                                                                        <option value="">Pilih Relasi</option>
                                                                        <?php $get_user_relation = get_user_relation($u['id']); ?>
                                                                        <?php foreach ($relation as $r) : ?>
                                                                            <?php $select = (in_array($r['id_pelanggan'], $get_user_relation['relation_id'])) ? 'selected' : ''; ?>
                                                                            <option value="<?= $r['id_pelanggan'] ?>" <?= $select ?>><?= $r['nama_pelanggan'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="submit" value="edit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<form action="<?= base_url('admin/settings/save_user'); ?>" method="POST">
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="f_name">Full Name</label>
                        <input type="text" class="form-control" id="f_name" name="f_name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <small id="usernameHelp" class="form-text text-danger">Harap diperhatikan bahwa username tidak dapat diubah.</small>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                        <input type="hidden" name="currentPassword" value="<?= $u['password']; ?>" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role_id">User Role</label>
                        <select class="form-control mySelect2" id="role_id" name="role_id">
                            <option value="">Pilih User Role</option>
                            <?php foreach ($user_role as $ur) : ?>
                                <option value="<?= $ur['role_id'] ?>"><?= $ur['role_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" value="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.getElementById('f_name').addEventListener('input', function() {
        const fullName = this.value.trim().toLowerCase();
        const usernameInput = document.getElementById('username');

        if (fullName.length > 0) {
            // Convert to username-like format
            const nameParts = fullName.split(' ');
            const baseUsername = nameParts.join('_'); // contoh: budi.santoso

            // Tambahkan angka random 2 digit (bisa disesuaikan)
            const randomNum = Math.floor(Math.random() * 90 + 10); // antara 10–99

            // Gabungkan
            const finalUsername = baseUsername + randomNum;

            // Set ke input username
            usernameInput.value = finalUsername;
        } else {
            usernameInput.value = '';
        }
    });
</script>


<?= $this->endSection(); ?>
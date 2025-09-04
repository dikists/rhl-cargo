<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\PelangganModel;
use App\Models\RelationUserModel;
use App\Models\UserRoleModel;

class UserController extends BaseController
{
  protected $adminModel;
  protected $userRoleModel;
  protected $pelangganModel;
  protected $relationUser;
  public function __construct()
  {
    $this->adminModel = new AdminModel();
    $this->userRoleModel = new UserRoleModel();
    $this->pelangganModel = new PelangganModel();
    $this->relationUser = new RelationUserModel();
  }
  public function ubahPassword()
  {
    $data = [
      'title' => 'Ubah Password',
      'user' => $this->currentUser
    ];
    // Tampilkan form ubah password
    return view('dashboard/ubah_password', $data);
  }
  public function prosesUbahPassword()
  {
    $session = session();
    $user = $this->currentUser;

    $currentPassword = $this->request->getPost('current_password');
    $newPassword = $this->request->getPost('new_password');
    $confirmPassword = $this->request->getPost('confirm_password');

    // Validasi password baru
    if ($newPassword !== $confirmPassword) {
      $session->setFlashdata('error', 'Password baru dan konfirmasi password tidak cocok.');
      return redirect()->to('ubah-password');
    }

    // Cek password lama
    if (!password_verify($currentPassword, $user['password'])) {
      $session->setFlashdata('error', 'Password lama salah.');
      return redirect()->to('ubah-password');
    }

    // Hash password baru
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password di database
    $this->userModel->update($user['id_pelanggan'], ['password' => $hashedPassword]);

    $session->setFlashdata('success', 'Password berhasil diubah.');
    return redirect()->to('ubah-password');
  }
  public function index()
  {
    $data['user_role'] = $this->userRoleModel->getRole();
    $data['users'] = $this->adminModel->getDataUsers();
    $data['relation'] = $this->pelangganModel->getPelanggan_new();
    $data['title'] = 'Daftar User';
    return view('transaksi/user/index', $data);
  }
  public function store()
  {
    $f_name = $this->request->getPost('f_name');
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('newPassword');
    $role = $this->request->getPost('role_id');

    $data = [
      'full_name' => $f_name,
      'username' => $username,
      'password' =>  password_hash($password, PASSWORD_DEFAULT),
      'role_id' => $role,
    ];

    $this->adminModel->insert($data);
    return redirect()->to('admin/settings/user')->with('success', 'Data berhasil disimpan.');
  }
  public function update($id)
  {
    $f_name = $this->request->getPost('f_name');
    $role = $this->request->getPost('role_id');

    $data = [
      'full_name' => $f_name,
      'role_id' => $role,
    ];
    if ($this->request->getPost('newPassword') != "") {
      $data['password'] = password_hash($this->request->getPost('newPassword'), PASSWORD_DEFAULT);
    }

    $this->adminModel->update($id, $data);
    return redirect()->to('admin/settings/user')->with('success', 'Data berhasil diubah.');
  }
  public function delete($id)
  {
    $data = [
      'deleted_at' => get_time()
    ];

    if ($this->adminModel->update($id, $data)) {
      return redirect()->to('admin/settings/user')->with('success', 'Data berhasil dihapus');
    } else {
      return redirect()->back()->with('error', 'Gagal menghapus data');
    }
  }
  public function update_relation_user($id)
  {
    $relation_list = $this->request->getPost('relation_list');

    $this->relationUser->delete_user_relation($id);

    if (count($relation_list) > 0) {

      for ($i = 0; $i < count($relation_list); $i++) {
        $data = [
          'relation_id' => $relation_list[$i],
          'user_id' => $id,
          'create_at' => get_time(),
          'create_by' => session()->get('id')
        ];

        $this->relationUser->insert($data);
      }
    }

    return redirect()->to('admin/settings/user')->with('success', 'Data berhasil diubah.');

  }
}

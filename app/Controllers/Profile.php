<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Profile extends BaseController
{
     protected $adminModel;
     public function __construct()
     {
          $this->adminModel = new AdminModel();
     }
     public function index()
     {
          $user = $this->currentUser;
          $data = [
               'title' => 'Profile',
               'user' => $user
          ];
          echo view('profile/index', $data);
     }
     public function profile()
     {
          $id = session()->get('id');
          $user = $this->adminModel->getDataUsers($id);
          $data = [
               'title' => 'Profile',
               'user' => $user
          ];
          echo view('profile/profile', $data);
     }
     public function update_profile()
     {
          $id = $this->request->getPost('id');
          $data = [
               'full_name' => $this->request->getPost('full_name'),
               'email' => $this->request->getPost('email'),
               'telepon' => $this->request->getPost('telepon'),
          ];

          $save = $this->adminModel->update($id, (object) $data);

          if ($save) {
               return redirect()->to(base_url('admin/profile'))->with('success', 'Data berhasil diubah');
          } else {
               return redirect()->to(base_url('admin/profile'))->with('error', 'Data gagal diubah');
          }
     }
     public function update_password()
     {
          $id = $this->request->getPost('id');
          $old_password = $this->request->getPost('old_password');
          $new_password = $this->request->getPost('new_password');
          $confirm_password = $this->request->getPost('confirm_password');

          $user = $this->adminModel->getDataUsers($id);
          $pass = $user['password'];
          $authenticatePassword = password_verify($old_password, $pass);

          if ($authenticatePassword == false) {
               return redirect()->to(base_url('admin/ubah-password'))->with('error', 'Password lama salah');
          }

          if ($new_password != $confirm_password) {
               return redirect()->to(base_url('admin/ubah-password'))->with('error', 'Konfirmasi password tidak sama');
          }

          $data = [
               'password' => password_hash($new_password, PASSWORD_DEFAULT),
          ];

          $save = $this->adminModel->update($id, (object) $data);

          if ($save) {
               return redirect()->to(base_url('admin/ubah-password'))->with('success', 'Data berhasil diubah');
          } else {
               return redirect()->to(base_url('admin/profile'))->with('error', 'Data gagal diubah');
          }
     }
     public function update_foto()
     {
          $foto = $this->request->getFile('foto');
          if ($foto->isValid() && !$foto->hasMoved()) {
               $newName = $foto->getRandomName();
               $foto->move(FCPATH . 'assets/img/users', $newName);

               $fotoLama = $this->request->getPost('fotolama');
               if (!empty($fotoLama)) {
                    $path = FCPATH . 'assets/img/users' . '/' . $fotoLama;
                    if ($fotoLama != 'default.jpg') {
                         if (file_exists($path)) {
                              unlink($path);
                         }
                    }
               }
          }

          $data = [
               'foto' => $newName,
          ];

          $id = session()->get('id');
          $this->adminModel->update($id, $data);

          return $this->response->setJSON([
               'success' => true,
               'message' => 'Foto berhasil diubah!'
          ]);
     }
     public function edit_password()
     {
          $id = session()->get('id');
          $user = $this->adminModel->getDataUsers($id);
          // echo "<pre>";
          // print_r($user);
          // echo "</pre>";
          // die;
          $data = [
               'title' => 'Profile',
               'user' => $user
          ];
          echo view('profile/edit_password', $data);
     }
}

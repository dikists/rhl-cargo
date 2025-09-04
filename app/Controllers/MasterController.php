<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\BarangModel;
use App\Models\VendorModel;
use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\BankAccountModel;
use App\Controllers\BaseController;
use App\Models\ShipmentStatusModel;

class MasterController extends BaseController
{
     protected $barangModel;
     protected $choiceListModel;
     protected $rekeningModel;
     protected $pelangganModel;
     protected $penerimaModel;
     protected $statusModel;
     protected $adminModel;
     protected $vendorModel;
     public function __construct()
     {
          $this->barangModel = new BarangModel();
          $this->choiceListModel = new ChoiceListModel();
          $this->rekeningModel = new BankAccountModel();
          $this->pelangganModel = new PelangganModel();
          $this->penerimaModel = new PenerimaModel();
          $this->statusModel = new ShipmentStatusModel();
          $this->adminModel = new AdminModel();
          $this->vendorModel = new VendorModel();
     }
     public function getBarang()
     {
          $data = $this->barangModel->getBarang();
          foreach ($data as $index => $item) {
               $data[$index]['aksi'] = '<button class="btn btn-warning btn-sm editBarang" data-id="' . $item['id_barang'] . '" data-name="' . $item['nama_barang'] . '" data-satuan="' . $item['satuan'] . '"><i class="fa fa-edit"></i> Edit</button> 
                                  <button class="btn btn-danger btn-sm deleteBarang" data-id="' . $item['id_barang'] . '">
                                        <i class="fa fa-trash"></i> Hapus
                                   </button>
                                   ';
          }
          return $this->response->setJSON($data);
     }
     public function saveBarang()
     {
          $data = [
               'nama_barang' => $this->request->getPost('nama_barang'),
               'satuan' => $this->request->getPost('satuan'),
          ];

          $save = $this->barangModel->save($data);
          if ($save) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Barang berhasil disimpan'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Barang gagal disimpan'
               ]);
          }
     }
     public function updateBarang()
     {
          $id = $this->request->getPost('id');
          $data = [
               'nama_barang' => $this->request->getPost('nama_barang'),
               'satuan' => $this->request->getPost('satuan'),
          ];
          $update = $this->barangModel->update($id, (object) $data);
          if ($update) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Barang berhasil diubah'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Barang gagal diubah'
               ]);
          }
     }
     public function deleteBarang($id)
     {
          if ($this->barangModel->delete($id)) {
               return $this->response->setJSON(['success' => true, 'message' => 'Barang berhasil dihapus!']);
          } else {
               return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus barang!']);
          }
     }
     // Rekening
     public function getRekening()
     {
          $data = $this->rekeningModel->getRekening();
          foreach ($data as $index => $item) {
               $data[$index]['aksi'] = '<button class="btn btn-warning btn-sm editRekening" 
                                   data-id="' . $item['id'] . '" 
                                   data-holder_name="' . $item['account_holder_name'] . '" 
                                   data-account_number="' . $item['account_number'] . '" 
                                   data-bank_name="' . $item['bank_name'] . '" 
                                   data-account_type="' . $item['account_type'] . '" 
                                   data-balance="' . $item['balance'] . '"
                                   data-signatory="' . $item['signatory'] . '"><i class="fa fa-edit"></i> Edit</button> 
                                  <button class="btn btn-danger btn-sm deleteRekening" data-id="' . $item['id'] . '">
                                        <i class="fa fa-trash"></i> Hapus
                                   </button>
                                   ';
          }
          return $this->response->setJSON($data);
     }
     public function saveRekening()
     {
          $data = [
               'account_holder_name' => $this->request->getPost('holder_name'),
               'account_number' => $this->request->getPost('account_number'),
               'bank_name' => $this->request->getPost('bank_name'),
               'account_type' => $this->request->getPost('account_type'),
               'signatory' => $this->request->getPost('signatory'),
               'balance' => $this->request->getPost('balance'),
          ];
          $save = $this->rekeningModel->save($data);
          if ($save) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Rekening berhasil disimpan'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Rekening gagal disimpan'
               ]);
          }
     }
     public function updateRekening()
     {
          $id = $this->request->getPost('id');
          $data = [
               'account_holder_name' => $this->request->getPost('holder_name'),
               'account_number' => $this->request->getPost('account_number'),
               'bank_name' => $this->request->getPost('bank_name'),
               'account_type' => $this->request->getPost('account_type'),
               'signatory' => $this->request->getPost('signatory'),
               'balance' => $this->request->getPost('balance'),
          ];
          $update = $this->rekeningModel->update($id, (object) $data);
          if ($update) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Rekening berhasil diubah'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Rekening gagal diubah'
               ]);
          }
     }
     public function deleteRekening($id)
     {
          if ($this->rekeningModel->delete($id)) {
               return $this->response->setJSON(['success' => true, 'message' => 'Rekening berhasil dihapus!']);
          } else {
               return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus Rekening!']);
          }
     }

     // PELANGGAN
     public function getPelanggan()
     {
          $data = $this->pelangganModel->getPelanggan_new();

          foreach ($data as $index => $item) {
               $data[$index]['master'] = '<div class="btn-group btn-group-sm" role="group" aria-label="">
                                             <a href="' . base_url('relation-tax/' . $item['id_pelanggan']) . '" class="btn btn-outline-info mt-1 mb-1" data-id="' . $item['id_pelanggan'] . '">
                                               <i class="fa fa-tag"></i> PPN (' . $item['ppn'] . ')
                                             </a>
                                             <a href="' . base_url('relation-tax-pph/' . $item['id_pelanggan']) . '" class="btn btn-outline-info mt-1 mb-1" data-id="' . $item['id_pelanggan'] . '">
                                                PPH (' . $item['pph'] . ')
                                             </a>
                                        </div>';
               $data[$index]['aksi'] = '
                                   <div class="text-center">
                                        <button class="btn btn-warning btn-sm editPelanggan m-1" 
                                                  data-id="' . $item['id_pelanggan'] . '" 
                                                  data-nama_pelanggan="' . $item['nama_pelanggan'] . '" 
                                                  data-alamat_pelanggan="' . $item['alamat_pelanggan'] . '" 
                                                  data-kota="' . $item['kota'] . '" 
                                                  data-telepon_pelanggan="' . $item['telepon_pelanggan'] . '" 
                                                  data-email="' . $item['email'] . '" 
                                                  data-top="' . $item['top'] . '" data-npwp="' . $item['npwp'] . '"><i class="fa fa-edit"></i> Edit
                                        </button> 
                                        <button class="btn btn-danger btn-sm deletePelanggan m-1" data-id="' . $item['id_pelanggan'] . '">
                                             <i class="fa fa-trash"></i> Hapus
                                        </button>
                                   </div>
                                   ';
          }
          return $this->response->setJSON($data);
     }
     public function savePelanggan()
     {
          $data = [
               'nama_pelanggan' => $this->request->getPost('nama'),
               'alamat_pelanggan' => $this->request->getPost('alamat'), // Pastikan kolom yang benar
               'kota' => $this->request->getPost('kota'),
               'telepon_pelanggan' => $this->request->getPost('telp'),
               'email' => $this->request->getPost('email'),
               'top' => $this->request->getPost('top'),
               'npwp' => $this->request->getPost('npwp'),
               'password' => password_hash('123456', PASSWORD_DEFAULT),
               'status' => 1
          ];

          if ($this->pelangganModel->save($data)) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Pelanggan berhasil disimpan'
               ]);
          } else {
               // Ambil error dari model
               $errors = $this->pelangganModel->errors();

               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pelanggan gagal disimpan',
                    'errors' => $errors
               ]);
          }
     }
     public function updatePelanggan()
     {
          $id = $this->request->getPost('id');
          $data = [
               'nama_pelanggan' => $this->request->getPost('nama'),
               'alamat_pelanggan' => $this->request->getPost('alamat'), // Pastikan kolom yang benar
               'kota' => $this->request->getPost('kota'),
               'telepon_pelanggan' => $this->request->getPost('telp'),
               'email' => $this->request->getPost('email'),
               'top' => $this->request->getPost('top'),
               'npwp' => $this->request->getPost('npwp'),
          ];

          if ($this->pelangganModel->update($id, (object) $data)) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Pelanggan berhasil diupdate'
               ]);
          } else {
               // Ambil error dari model
               $errors = $this->pelangganModel->errors();

               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pelanggan gagal diupdate',
                    'errors' => $errors
               ]);
          }
     }
     public function deletePelanggan($id)
     {
          if ($this->pelangganModel->delete($id)) {
               return $this->response->setJSON(['success' => true, 'message' => 'Pelanggan berhasil dihapus!']);
          } else {
               return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus pelanggan!']);
          }
     }
     // PENERIMA
     public function getPenerima()
     {
          $data = $this->penerimaModel->getPenerima();
          foreach ($data as $index => $item) {
               $data[$index]['aksi'] = '<div class="text-center">
                                             <a href="' . base_url('admin/penerima/edit/' . $item['id_penerima']) . '" class="btn btn-info btn-sm m-1"><i class="fa fa-edit"></i> Edit</a>
                                             <button class="btn btn-danger btn-sm m-1 deletePenerima" data-id="' . $item['id_penerima'] . '">
                                                  <i class="fa fa-trash"></i> Hapus
                                             </button>
                                        </div>
                                   ';
          }
          return $this->response->setJSON($data);
     }
     public function savePenerima()
     {
          // Validasi data form
          if (!$this->validate([
               'pelanggan' => 'required',
               'email' => 'required|valid_email',
               'penerima' => 'required',
               'telp' => 'required',
               'alamat' => 'required',
               'prov' => 'required',
               'kab' => 'required',
               'kec' => 'required',
               'des' => 'required',
          ])) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          // Data yang akan disimpan
          $data = [
               'id_pelanggan' => $this->request->getPost('pelanggan'),
               'email_penerima' => $this->request->getPost('email'),
               'nama_penerima' => $this->request->getPost('penerima'),
               'telepon_penerima' => $this->request->getPost('telp'),
               'alamat_penerima' => $this->request->getPost('alamat'),
               'provinsi_id' => $this->request->getPost('prov'),
               'kabupaten_id' => $this->request->getPost('kab'),
               'kecamatan_id' => $this->request->getPost('kec'),
               'desa_id' => $this->request->getPost('des'),
          ];

          if ($this->request->getPost('id') == null) {
               $this->penerimaModel->save($data);
          } else {
               $this->penerimaModel->update($this->request->getPost('id'), $data);
          }

          // Redirect dan berikan pesan sukses
          return redirect()->to('/admin/penerima')->with('success', 'Data penerima berhasil disimpan');
     }
     public function deletePenerima($id)
     {
          if ($this->penerimaModel->delete($id)) {
               return $this->response->setJSON(['success' => true, 'message' => 'Penerima berhasil dihapus!']);
          } else {
               return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus Penerima!']);
          }
     }
     // SHIPMENT STATUS
     public function getshipmentStatus()
     {
          $data = $this->statusModel->orderBy('status_id', 'DESC')->findAll();
          foreach ($data as $index => $item) {
               $data[$index]['aksi'] = '<div class="text-center"><button class="btn btn-warning btn-sm m-1 editStatus" data-id="' . $item['status_id'] . '" data-status_name="' . $item['status_name'] . '"><i class="fa fa-edit"></i> Edit</button> 
                                        <button class="btn btn-danger btn-sm m-1 deleteStatus" data-id="' . $item['status_id'] . '">
                                                  <i class="fa fa-trash"></i> Hapus
                                        </button></div>
                                   ';
          }
          return $this->response->setJSON($data);
     }
     public function saveStatus()
     {
          $data = [
               'status_name' => $this->request->getPost('status_name'),
          ];
          $save = $this->statusModel->save($data);
          if ($save) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Status berhasil disimpan'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Status gagal disimpan'
               ]);
          }
     }
     public function updateStatus()
     {
          $data = [
               'status_name' => $this->request->getPost('status_name'),
          ];
          $update = $this->statusModel->update($this->request->getPost('id'), $data);
          if ($update) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Status berhasil diubah'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Status gagal diubah'
               ]);
          }
     }
     public function deleteStatus($id)
     {
          $delete = $this->statusModel->delete($id);
          if ($delete) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Status berhasil dihapus'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Status gagal dihapus'
               ]);
          }
     }
     public function getUsers()
     {

          $data = $this->adminModel->getDataUsers();
          $no = 1;
          foreach ($data as $index => $item) {
               $data[$index]['no'] = $no++;
               $data[$index]['aksi'] = '<div class="text-center">
               <a href="' . base_url('admin/users/edit/' . $item['id']) . '"><button class="btn btn-primary btn-sm m-1"><i class="fa fa-edit"></i> Edit</button></a>
               <button class="btn btn-danger btn-sm m-1 deleteUsers" data-id="' . $item['id'] . '">
               <i class="fa fa-trash"></i> Hapus
               </button></div>
               ';
               $data[$index]['image'] = '<div class="text-center"> <img class="img-fluid align-middle " src="' . base_url('assets/img/users/' . $item['foto']) . '" width="50px" height="50px" alt="' . $item['full_name'] . '" /></div>';
          }
          return $this->response->setJSON($data);
     }
     public function saveUsers()
     {
          $foto = $this->request->getFile('foto');

          $validate = $this->validate([
               'full_name' => 'required',
               'telepon' => 'required',
               'email' => 'required|valid_email',
               'username' => 'required',
               'role_id' => 'required',
          ]);

          if ($this->request->getPost('id') == null) {
               $validate = $this->validate([
                    'password' => 'required',
               ]);
          }

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          if (!empty($foto) && $foto->isValid() && !$foto->hasMoved()) {
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
          } else {
               $newName = 'default.jpg';

               $fotoLama = $this->request->getPost('fotolama');
               if ($this->request->getPost('id') !== null && !empty($fotoLama)) {
                    $newName = $fotoLama;
               }
          }


          $data = [
               'full_name' => $this->request->getPost('full_name'),
               'telepon' => $this->request->getPost('telepon'),
               'email' => $this->request->getPost('email'),
               'foto' => $newName,
               'username' => $this->request->getPost('username'),
               'role_id' => $this->request->getPost('role_id'),
          ];

          if ($this->request->getPost('password') !== '') {
               $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
          }

          if ($this->request->getPost('id') == null) {
               $save = $this->adminModel->save($data);
          } else {
               $save = $this->adminModel->update($this->request->getPost('id'), (object) $data);
          }
          if ($save) {
               return redirect()->to('/admin/admin/users')->with('success', 'Data User berhasil disimpan');
          } else {
               return redirect()->to('/admin/users/add')->with('success', 'Data User berhasil disimpan');
          }
     }
     public function deleteUsers($id)
     {
          $delete = $this->adminModel->delete($id);
          if ($delete) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'User berhasil dihapus'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'User gagal dihapus'
               ]);
          }
     }
     public function getVendor()
     {
          $data = $this->vendorModel->getVendor();
          foreach ($data as $index => $item) {
               $data[$index]['aksi'] = '<div class="text-center">
               <button class="btn btn-warning btn-sm m-1 editVendor" data-id="' . $item['vendor_id'] . '" data-name="' . $item['vendor_name'] . '" data-email="' . $item['vendor_email'] . '" data-group_phone="' . $item['vendor_group_phone'] . '" data-phone="' . $item['vendor_phone'] . '" data-address="' . $item['vendor_address'] . '">
                <i class="fa fa-edit"></i> Edit
               </button>
               <button class="btn btn-danger btn-sm m-1 deleteVendor" data-id="' . $item['vendor_id'] . '">
                    <i class="fa fa-trash"></i> Hapus
               </button></div>
               ';
          }
          return $this->response->setJSON($data);
     }
     public function updateVendor()
     {
          $id = $this->request->getPost('vendor_id');

          $data = [
               'vendor_name' => $this->request->getPost('vendor_name'),
               'short_name' => $this->request->getPost('short_name'),
               'vendor_email' => $this->request->getPost('vendor_email'),
               'vendor_phone' => $this->request->getPost('vendor_phone'),
               'vendor_group_phone' => $this->request->getPost('vendor_group_phone'),
               'vendor_address' => $this->request->getPost('vendor_address'),
          ];
          $save = $this->vendorModel->update($id, (object) $data);
          if ($save) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Vendor berhasil diubah'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Vendor gagal diubah'
               ]);
          }
     }
     public function saveVendor()
     {

          $data = [
               'vendor_name' => $this->request->getPost('vendor_name'),
               'short_name' => $this->request->getPost('short_name'),
               'vendor_email' => $this->request->getPost('vendor_email'),
               'vendor_phone' => $this->request->getPost('vendor_phone'),
               'vendor_group_phone' => $this->request->getPost('vendor_group_phone'),
               'vendor_address' => $this->request->getPost('vendor_address'),
          ];
          $save = $this->vendorModel->save($data);
          if ($save) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Vendor berhasil disimpan'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Vendor gagal disimpan'
               ]);
          }
     }
     public function deleteVendor($id)
     {
          $delete = $this->vendorModel->delete($id);
          if ($delete) {
               return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Vendor berhasil dihapus'
               ]);
          } else {
               return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Vendor gagal dihapus'
               ]);
          }
     }
}

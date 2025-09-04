<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\ChoiceListModel;

class BarangController extends BaseController
{
     protected $barangModel;
     protected $choiceListModel;
     public function __construct()
     {
          $this->barangModel = new BarangModel();
          $this->choiceListModel = new ChoiceListModel();
     }
     public function index()
     {
          $data = [
               'title' => 'Daftar Barang',
               'satuan' => $this->choiceListModel->getChoicesByType('satuan')
          ];
          echo view('barang/index', $data);
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
}

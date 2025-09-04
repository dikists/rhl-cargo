<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\RelationPphModel;
use CodeIgniter\RESTful\ResourceController;

class RelationPphController extends ResourceController
{
     protected $relationPphModel;
     protected $pelangganModel;
     protected $choiceListModel;

     public function __construct()
     {
          $this->relationPphModel = new RelationPphModel();
          $this->pelangganModel = new PelangganModel();
          $this->choiceListModel = new ChoiceListModel();
     }

     public function show($id = null)
     {
          $pelanggan = $this->pelangganModel->getPelanggan($id);
          $tax_pph = $this->relationPphModel->getTaxPph($id);
          $data = [
               'title' => 'PPH Pelanggan',
               'pelanggan' => $pelanggan,
               'tax_pph' => $tax_pph,
               'category' => $this->choiceListModel->getChoicesByType('receipt_category')
          ];
          echo view('master/relation_tax_pph', $data);
     }

     // Menyimpan data baru
     public function create()
     {
          $relation_id = $this->request->getPost('pelanggan_id');
          $data = [
               'pelanggan_id' => $this->request->getPost('pelanggan_id'),
               'category_id'  => $this->request->getPost('choice_id'),
               'percent'      => $this->request->getPost('pph'),
               'date_start'   => $this->request->getPost('start_date'),
               'date_end'     => $this->request->getPost('end_date') ?: null,
               'description'  => $this->request->getPost('description'),
               'created_by'   => session()->get('id')
          ];

          if ($this->relationPphModel->save($data)) {
               return redirect()->to('/relation-tax-pph/' . $relation_id)->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }

     }

     // Mengupdate data
     public function update($id_pelanggan = null)
     {
          $id = $this->request->getPost('id');
          $relation_id = $this->request->getPost('pelanggan_id');
          $data = [
               'pelanggan_id' => $this->request->getPost('pelanggan_id'),
               'category_id'  => $this->request->getPost('choice_id'),
               'percent'      => $this->request->getPost('pph'),
               'date_start'   => $this->request->getPost('start_date'),
               'date_end'     => $this->request->getPost('end_date') ?: null,
               'description'  => $this->request->getPost('description'),
               'updated_by'   => session()->get('id')
          ];

          if ($this->relationPphModel->update($id, $data)) {
               return redirect()->to('/relation-tax-pph/' . $relation_id)->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }

     // Menghapus data (Soft Delete)
     public function delete($id = null)
     {
          $data = [
               'status'       => 0,
               'deleted_by'   => session()->get('id'),
               'updated_by'   => session()->get('id')
          ];
          $this->relationPphModel->update($id, $data);
          $this->relationPphModel->delete($id);
          return $this->respondDeleted(["message" => "Data berhasil dihapus"]);
     }
     public function getRelationTax($id = null){
          $id = $this->request->getPost('pelanggan_id');
          // $this->relationPphModel->getTaxPph($id);
          return $this->respond($this->relationPphModel->getTaxPph($id));
     }
}

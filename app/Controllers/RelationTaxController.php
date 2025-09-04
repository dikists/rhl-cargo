<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\RelationTaxModel;
use CodeIgniter\RESTful\ResourceController;

class RelationTaxController extends ResourceController
{
     protected $modelName = 'App\Models\RelationTaxModel';
     protected $format    = 'json';
     protected $pelangganModel;
     protected $choiceListModel;
     protected $relationTax;

     public function __construct()
     {
          $this->relationTax = new RelationTaxModel();
          $this->pelangganModel = new PelangganModel();
          $this->choiceListModel = new ChoiceListModel();
     }

     public function index()
     {
          return $this->respond($this->model->getAll());
     }

     public function show($id = null)
     {
          $pelanggan = $this->pelangganModel->getPelanggan($id);
          $rtax = $this->model->getTax($id);

          $data = [
               'title' => 'PPN Pelanggan',
               'pelanggan' => $pelanggan,
               'rtax' => $rtax,
               'category' => $this->choiceListModel->getChoicesByType('receipt_category'),
               'tax_values' => $this->relationTax->db_get_enum_array('rtax_value')
          ];
          echo view('master/relation_tax', $data);
     }

     public function create()
     {
          $id = $this->request->getPost('id');
          $relation_id = $this->request->getPost('relation_id');
          $choice_id = $this->request->getPost('choice_id');
          $ppn_type = $this->request->getPost('ppn_type');
          $tax_value = $this->request->getPost('tax_value');
          $tax_gunggung = $this->request->getPost('tax_gunggung');
          $date_start = $this->request->getPost('date_start');
          $date_end = $this->request->getPost('date_end');
          $description = $this->request->getPost('description');

          $data = [
               'rtax_relation_id' => $relation_id,
               'rtax_category_id' => $choice_id,
               'rtax_ppn_type' => $ppn_type,
               'rtax_value' => $tax_value,
               'rtax_gunggung' => $tax_gunggung,
               'rtax_date_start' => $date_start,
               'rtax_date_end' => $date_end ? $date_end : null,
               'rtax_desc' => $description,
               'rtax_create_at' => date('Y-m-d H:i:s'),
               'rtax_create_by' => session()->get('id'),
          ];

          // Insert data ke database
          if ($this->relationTax->save($data)) {
               return redirect()->to('/relation-tax/' . $relation_id)->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }

     public function update($id_pelanggan = null)
     {
          $id            = $this->request->getPost('rtax_id');
          $relation_id   = $this->request->getPost('relation_id');
          $choice_id     = $this->request->getPost('choice_id');
          $ppn_type      = $this->request->getPost('ppn_type');
          $tax_value     = $this->request->getPost('tax_value');
          $tax_gunggung  = $this->request->getPost('tax_gunggung');
          $date_start    = $this->request->getPost('date_start');
          $date_end      = !empty($this->request->getPost('date_end'))
               ? date_format(date_create($this->request->getPost('date_end')), "Y-m-d")
               : null;
          $description   = $this->request->getPost('description');

          $data = [
               'rtax_relation_id' => $relation_id,
               'rtax_category_id' => $choice_id,
               'rtax_ppn_type' => $ppn_type,
               'rtax_value' => $tax_value,
               'rtax_gunggung' => $tax_gunggung,
               'rtax_date_start' => $date_start,
               'rtax_date_end' => $date_end ? $date_end : null,
               'rtax_desc' => $description,
               'rtax_create_at' => date('Y-m-d H:i:s'),
               'rtax_create_by' => session()->get('id'),
          ];

          if ($this->relationTax->update($id, $data)) {
               return redirect()->to('/relation-tax/' . $relation_id)->with('success', 'Data berhasil diubah.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
          }
     }

     public function delete($id = null)
     {
          if ($this->model->deleteData($id)) {
               return $this->respondDeleted(['message' => 'Data berhasil dihapus']);
          }
          return $this->fail('Gagal menghapus data.');
     }

     public function getRelationTax($id = null){
          $id = $this->request->getPost('pelanggan_id');
          return $this->respond($this->model->getTax($id));
     }
}

<?php

namespace App\Controllers;

use App\Models\OfferDetailsModel;
use CodeIgniter\RESTful\ResourceController;

class OfferDetailsController extends ResourceController
{
     protected $modelName = 'App\\Models\\OfferDetailsModel';
     protected $format    = 'json';

     public function index()
     {
          return $this->respond($this->model->findAll());
     }

     public function show($id = null)
     {
          $data = $this->model->find($id);
          if ($data) {
               return $this->respond($data);
          }
          return $this->failNotFound('Data not found');
     }

     public function store()
     {
          $offer_id = $this->request->getPost('offer_id');
          $data = $this->request->getPost();
          $insert = $this->model->insert($data);

          if ($insert) {
               return redirect()->to('admin/offer-details/' . $offer_id)->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
          }
     }

     public function update($id = null)
     {
          $data = $this->request->getRawInput();
          if ($this->model->update($id, $data)) {
               return $this->respond($data);
          }
          return $this->fail('Failed to update data');
     }

     public function delete($id = null, $head = null)
     {
          if ($this->model->delete($id)) {
               return redirect()->to('admin/offer-details/' . $head)->with('success', 'Data berhasil dihapus.');
          }
          return redirect()->back()->withInput()->with('error', 'Gagal menghapus data.');
     }
}

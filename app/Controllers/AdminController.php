<?php

namespace App\Controllers;

use App\Models\PenerimaModel;
use App\Models\PengirimanModel;
use App\Controllers\BaseController;

class AdminController extends BaseController
{
     protected $PengirimanModel;
     protected $PenerimaModel;
     public function __construct()
     {
          $this->PengirimanModel = new PengirimanModel();
          $this->PenerimaModel = new PenerimaModel();
     }
     public function dashboard()
     {
          $data = [
               'title' => 'Dashboard'
          ];
          $id = session()->get('id');
          // $data['stats'] =  $this->PengirimanModel->getShipmentStats(customerId: $id, isAdmin: $admin);
          $data['stats'] =  $this->PengirimanModel->getSummaryByYear($id);
          // dd($data['stats']);
          echo view('admin/dashboard', $data);
     }
}

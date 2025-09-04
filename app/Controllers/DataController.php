<?php

namespace App\Controllers;

use App\Models\DesaModel;
use App\Models\RoleModel;
use App\Models\BarangModel;
use App\Models\LayananModel;
use App\Models\PenerimaModel;
use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\BankAccountModel;
use App\Controllers\BaseController;

class DataController extends BaseController
{
     protected $barangModel;
     protected $choiceListModel;
     protected $rekeningModel;
     protected $pelangganModel;
     protected $penerimaModel;
     protected $provinsiModel;
     protected $kabModel;
     protected $kecModel;
     protected $desModel;
     protected $roleModel;
     protected $layananModel;
     public function __construct()
     {
          $this->barangModel = new BarangModel();
          $this->choiceListModel = new ChoiceListModel();
          $this->rekeningModel = new BankAccountModel();
          $this->pelangganModel = new PelangganModel();
          $this->penerimaModel = new PenerimaModel();
          $this->provinsiModel = new ProvinsiModel();
          $this->kabModel = new KabupatenModel();
          $this->kecModel = new KecamatanModel();
          $this->desModel = new DesaModel();
          $this->roleModel = new RoleModel();
          $this->layananModel = new LayananModel();
     }
     public function getKabupaten($id)
     {
          $data = $this->kabModel->getKabupaten($id);
          return $this->response->setJSON($data);
     }
     public function getKecamatan($id)
     {
          $data = $this->kecModel->getKecamatan($id);
          return $this->response->setJSON($data);
     }
     public function getDesa($id)
     {
          $data = $this->desModel->getDesa($id);
          return $this->response->setJSON($data);
     }
     public function get_penerima_by_pengirim($id)
     {
          $data = $this->penerimaModel->getPenerimaByPengirim($id);
          echo json_encode($data);
     }
     public function get_layanan_new_order()
     {
          $pengirim = $this->request->getGet('id_pengirim');
          $penerima = $this->request->getGet('id_penerima');
          $data = $this->layananModel->getLayananNewOrder($pengirim, $penerima);
          
          echo json_encode($data);
     }
}

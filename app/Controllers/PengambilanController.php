<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\OrderModel;
use App\Models\BarangModel;
use App\Models\VendorModel;
use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\SuratJalanModel;
use App\Models\BankAccountModel;
use App\Models\DetailOrderModel;
use App\Models\PengambilanModel;
use App\Controllers\BaseController;
use App\Models\ShipmentStatusModel;
use App\Models\DetailOrderBiayaModel;

class PengambilanController extends BaseController
{
     protected $barangModel;
     protected $choiceListModel;
     protected $rekeningModel;
     protected $pelangganModel;
     protected $penerimaModel;
     protected $statusModel;
     protected $adminModel;
     protected $vendorModel;
     protected $orderModel;
     protected $detailOrderModel;
     protected $detailOrderBiayaModel;
     protected $suratJalanModel;
     protected $pengambilanModel;
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
          $this->orderModel = new OrderModel();
          $this->detailOrderModel = new DetailOrderModel();
          $this->detailOrderBiayaModel = new DetailOrderBiayaModel();
          $this->suratJalanModel = new SuratJalanModel();
          $this->pengambilanModel = new PengambilanModel();
     }
     public function getPengambilan()
     {
          $data = $this->pengambilanModel->getPengambilan();
          // dd($data);
          $no = 1;
          foreach ($data as $index => $item) {
               $aksi = "";
               if ($item['in_manifest'] == 0) {
                    $aksi = '<div class="text-center">
                                        <a href="' . base_url('data/pengambilan/delete/' . $item['id_pengambilan']) . '" class="btn btn-danger btn-sm m-1" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-times"></i> Cancel</a>
                                   </div>
                              ';
               }
               $data[$index]['no'] = $no++;
               $data[$index]['aksi'] = $aksi;
          }
          return $this->response->setJSON($data);
     }

     public function savePengambilan()
     {
          $validate = $this->validate([
               'id_order' => 'required',
               'id_user' => 'required',
               'id_surat_jalan' => 'required',
          ]);

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          $id_order = $this->request->getVar('id_order');
          $id_user = $this->request->getVar('id_user');
          $id_surat_jalan = $this->request->getVar('id_surat_jalan');

          $data = [
               'id_order' => $id_order,
               'id_surat_jalan' => $id_surat_jalan,
               'kode_kurir' => $id_user,
               'tanggal_ambil' => date('Y-m-d'),
               'waktu_ambil' => date('H:i:s')
          ];

          if ($this->pengambilanModel->save($data)) {
               return redirect()->to('/admin/pengambilan')->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }
     public function deletePengambilan($id)
     {
          // dd($id);
          $this->pengambilanModel->delete($id);
          return redirect()->to('/admin/pengambilan')->with('success', 'Data berhasil dihapus.');
     }
}

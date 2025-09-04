<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\OrderModel;
use App\Models\BarangModel;
use App\Models\VendorModel;
use App\Models\ManifestModel;
use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\SuratJalanModel;
use App\Models\BankAccountModel;
use App\Models\DetailOrderModel;
use App\Models\PengambilanModel;
use App\Controllers\BaseController;
use App\Models\ManifestDetailModel;
use App\Models\ShipmentStatusModel;
use App\Models\DetailOrderBiayaModel;

class ManifestController extends BaseController
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
     protected $manifestModel;
     protected $manifestDetailModel;
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
          $this->manifestModel = new ManifestModel();
          $this->manifestDetailModel = new ManifestDetailModel();
     }
     public function getManifest()
     {
          $data = $this->manifestModel->getManifest();
          // echo "<pre>";
          // print_r($data);
          // echo "</pre>";
          // die;
          $no = 1;
          foreach ($data as $index => $item) {
               // Decode JSON array menjadi array PHP
               $detail_list = json_decode($item['detail_list'], true);

               // Buat elemen list HTML jika ada data
               $listHtml = $print = '';
               $hapus = '
                         <a href="' . base_url('data/deleteManifest/' . $item['manifest_id']) . '" class="dropdown-item" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash"></i> Delete</a>
               ';

               if ($item['total_detail'] > 0) {
                    // Buat elemen list HTML jika ada data
                    $listHtml .= '<ul style="list-style-type: none;">';
                    foreach ($detail_list as $detail) {
                         $listHtml .= '<li>' . htmlspecialchars($detail['no_surat_jalan']) . ' <a href="' . base_url('pdf/manifest_sub/' . $detail['no_surat_jalan']) . '" target="_blank"><i class="fa fa-print"></i></a> </li>';
                    }
                    $listHtml .= '</ul>';

                    $hapus = '';
                    $print = '
                              <a href="' . base_url('pdf/manifest/' . $item['manifest_id']) . '" target="_blank" class="dropdown-item"><i class="fa fa-print"></i> Cetak INT</a>
                    ';
                    $print2 = '
                              <a href="' . base_url('pdf/manifest_rincian/' . $item['manifest_id']) . '" target="_blank" class="dropdown-item"><i class="fa fa-print"></i> Cetak Rincian</a>
                    ';
               }

               $data[$index]['no'] = $no++;
               $data[$index]['no_surat_jalan_list'] = $listHtml;
               $data[$index]['aksi'] = '
                                        <div class="btn-group dropleft">
                                             <a href="#" data-toggle="dropdown" aria-expanded="false">
                                                  <i class="fa fa-list fa-2x" aria-hidden="true"></i>
                                             </a>
                                             <div class="dropdown-menu">
                                                  <a href="' . base_url('admin/manifest/edit/' . $item['manifest_id']) . '" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                  <a href="' . base_url('admin/manifest/detail/' . $item['manifest_id']) . '" class="dropdown-item"><i class="fa fa-list"></i> Detail</a>
                                                  ' . $print . '
                                                  ' . $print2 . '
                                                  ' . $hapus . '
                                             </div>
                                        </div>
                                   ';
          }
          return $this->response->setJSON($data);
     }

     public function saveManifest()
     {
          // dd($this->request->getVar());
          $validate = $this->validate([
               'date' => 'required',
               'vendor' => 'required',
          ]);

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          $date = $this->request->getVar('date');
          $vendor = $this->request->getVar('vendor');
          $data = [
               'date' => $date,
               'vendor_id' => $vendor,
          ];

          if ($this->manifestModel->save($data)) {
               $id = $this->manifestModel->getInsertID();
               return redirect()->to('admin/manifest/detail/' . $id)->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }
     public function updateManifest()
     {
          // dd($this->request->getVar());
          $validate = $this->validate([
               'date' => 'required',
               'vendor' => 'required',
          ]);

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          $id = $this->request->getVar('id');
          $date = $this->request->getVar('date');
          $vendor = $this->request->getVar('vendor');
          $data = [
               'date' => $date,
               'vendor_id' => $vendor,
          ];

          if ($this->manifestModel->update($id, $data)) {
               return redirect()->to('admin/manifest')->with('success', 'Data berhasil diubah.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }
     public function deleteManifest($id)
     {
          // dd($id);
          $this->manifestModel->delete($id);
          return redirect()->to('/admin/manifest')->with('success', 'Data berhasil dihapus.');
     }
     public function pdf_manifest($id)
     {
          ini_set('max_execution_time', 300);
          helper('pdf');
          $data = [
               'manifest' => $this->manifestModel->getManifest($id),
               'detail' => $this->manifestDetailModel->getPDFDetailManifest($id),
          ];

          $html = view('transaksi/manifest/pdf_manifest', $data);
          create_pdf($html, 'Manifest_' . $id . '.pdf');
          exit;
     }
}

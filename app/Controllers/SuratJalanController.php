<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\OrderModel;
use App\Models\BarangModel;
use App\Models\VendorModel;
use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\PengirimanModel;
use App\Models\SuratJalanModel;
use App\Models\BankAccountModel;
use App\Models\DetailOrderModel;
use App\Controllers\BaseController;
use App\Models\ShipmentStatusModel;
use App\Models\DetailOrderBiayaModel;

class SuratJalanController extends BaseController
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
     protected $pengirimanModel;
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
          $this->pengirimanModel = new PengirimanModel();
     }
     public function getSuratJalan()
     {
          $id = false;
          $date_start = $this->request->getGet('date_start');
          $date_end = $this->request->getGet('date_end');
          $pengirim = $this->request->getGet('pengirim');
          $penerima = $this->request->getGet('penerima');
          $layanan = $this->request->getGet('layanan');

          $data = $this->suratJalanModel->getAllSuratJalan($id, $date_start, $date_end, $pengirim, $penerima, $layanan);
          $no = 1;
          foreach ($data as $index => $item) {
               $delete = "";
               if ($item['in_pickup'] == 0) {
                    $delete = '<a href="' . base_url('data/surat_jalan/delete/' . $item['id_surat_jalan']) . '" class="btn btn-danger btn-sm m-1" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash"></i> Hapus</a>';
               }

               $aksi = '<div class="text-center">
                              <a href="' . base_url('pdf/cetak_resi/' . $item['id_surat_jalan']) . '" class="btn btn-success btn-sm m-1" target="_blank">
                                   <i class="fa fa-print"></i> Cetak Resi
                              </a>
                              <button data-id="' . $item['id_surat_jalan'] . '" type="button" class="btn btn-primary btn-sm m-1 cetakLabel" data-toggle="modal" data-target="#modalCetakLabel">
                                   <i class="fa fa-barcode"></i> Cetak Label
                              </button>
                              <a href="' . base_url('admin/surat_jalan/edit/' . $item['id_surat_jalan']) . '" class="btn btn-primary btn-sm m-1"><i class="fa fa-edit"></i> Edit</a> 
                              ' . $delete . '
                         </div>
                         ';
               $role = session()->get('role');
               if ($role == "PIC RELASI") {
                    $aksi = '<div class="text-center">
                                   <a href="' . base_url('pdf/cetak_resi/' . $item['id_surat_jalan']) . '" class="btn btn-success btn-sm m-1" target="_blank">
                                        <i class="fa fa-print"></i> Cetak Resi
                                   </a>
                              </div>';
               }

               $data[$index]['no'] = $no++;
               $data[$index]['aksi'] = $aksi;
          }
          return $this->response->setJSON($data);
     }

     public function saveSuratJalan()
     {
          $validate = $this->validate([
               'id_order' => [
                    'rules' => 'required',
                    'errors' => [
                         'required' => 'Order wajib dipilih.'
                    ]
               ],
               'no_surat_jalan' => [
                    'rules' => 'required|is_unique[tb_surat_jalan.no_surat_jalan]',
                    'errors' => [
                         'required'   => 'Nomor Surat Jalan wajib diisi.',
                         'is_unique'  => 'Nomor Surat Jalan sudah digunakan, silakan gunakan nomor lain.'
                    ]
               ],
               'driver' => [
                    'rules' => 'required',
                    'errors' => [
                         'required' => 'Driver wajib diisi.'
                    ]
               ],
               'nopol' => [
                    'rules' => 'required',
                    'errors' => [
                         'required' => 'Nomor Polisi wajib diisi.'
                    ]
               ]
          ]);

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          $data = [
               'id_order' => $this->request->getVar('id_order'),
               'no_surat_jalan' => $this->request->getVar('no_surat_jalan'),
               'kode_kurir' => $this->request->getVar('driver'),
               'truck_id' => $this->request->getVar('nopol'),
               'tgl_pembuatan' => date('Y-m-d'),
          ];

          if ($this->suratJalanModel->save($data)) {
               return redirect()->to('/admin/surat_jalan')->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }

     public function cetak_resi($id)
     {
          $db = \Config\Database::connect();
          ini_set('max_execution_time', 300);
          helper('pdf');
          $query = $db->table('tb_perusahaan')->get();
          $data['id'] = $id;
          $data['company'] = $query->getRowArray();
          $data['label'] = $this->suratJalanModel->getDatalabel($id);
          $data['pengiriman'] = $this->pengirimanModel->getPengirimanIdSJ($id);
          $data['barang'] = $this->detailOrderModel->getDetailOrders($data['label']['id_order']);


          // echo "<pre>";
          // print_r($data);
          // echo "</pre>";
          // die;

          $html = view('transaksi/surat_jalan/cetak_resi3', $data);
          create_resi_pdf($html, 'resi_' . $data['label']['no_surat_jalan'] . '.pdf');
          exit;
     }
     public function cetak_label()
     {
          $id = $this->request->getGet('id');
          $qty = $this->request->getGet('qty');
          $berat = $this->request->getGet('berat');

          ini_set('max_execution_time', 300);
          helper('pdf');
          $data['id'] = $id;
          $data['qty'] = $qty;
          $data['berat'] = $berat;
          $data['label'] = $this->suratJalanModel->getDatalabel($id);

          $html = view('transaksi/surat_jalan/cetak_label', $data);
          create_label_pdf($html, 'label_' . $data['label']['no_surat_jalan'] . '.pdf');
          exit;
     }
     public function updateSuratJalan()
     {
          var_dump($this->request->getVar());

          $id = $this->request->getVar('id_sj');
          $id_order = $this->request->getVar('id_order');
          $nosj = $this->request->getVar('no_surat_jalan');
          $driver = $this->request->getVar('driver');
          $nopol = $this->request->getVar('nopol');

          $data = [
               'id_order' => $id_order,
               'no_surat_jalan' => $nosj,
               'kode_kurir' => $driver,
               'truck_id' => $nopol,
          ];

          if ($this->suratJalanModel->update($id, (object) $data)) {
               return redirect()->to('/admin/surat_jalan')->with('success', 'Data berhasil diupdate.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal update data.');
          }
     }
     public function deleteSuratJalan($id)
     {
          $this->suratJalanModel->delete($id);
          return redirect()->to('/admin/surat_jalan')->with('success', 'Data berhasil dihapus.');
     }
     public function getSJManifest()
     {
          $data = $this->suratJalanModel->getSJManifest();
          return $this->response->setJSON($data);
     }
}

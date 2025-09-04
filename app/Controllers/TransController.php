<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\OrderModel;
use App\Models\BarangModel;
use App\Models\VendorModel;
use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\BankAccountModel;
use App\Models\DetailOrderModel;
use App\Controllers\BaseController;
use App\Models\ShipmentStatusModel;
use App\Models\DetailOrderBiayaModel;

class TransController extends BaseController
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
     }
     public function getOrder()
     {
          $id_order = false;
          $date_start = $this->request->getGet('date_start');
          $date_end = $this->request->getGet('date_end');
          $pengirim = $this->request->getGet('pengirim');
          $penerima = $this->request->getGet('penerima');
          $layanan = $this->request->getGet('layanan');

          $data = $this->orderModel->getAllOrder($id_order, $date_start, $date_end, $pengirim, $penerima, $layanan);
          $no = 1;
          foreach ($data as $index => $item) {
               $delete = "";
               if ($item['in_surat_jalan'] == 0) {
                    $delete = '<a href="' . base_url('data/deleteOrder/' . $item['id_order']) . '" class="btn btn-danger btn-sm mt-1" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash"></i> Hapus</a>';
               }

               $aksi = '<div class="text-center">
                                             <a href="' . base_url('admin/order/barang/' . $item['id_order']) . '" class="btn btn-info btn-sm mt-1">
                                                  <i class="fa fa-info"></i> Barang
                                             </a>
                                             <a href="' . base_url('admin/order/detail/' . $item['id_order']) . '" class="btn btn-success btn-sm mt-1">
                                                  <i class="fa fa-share"></i> Detail
                                             </a>
                                             <a href="' . base_url('admin/order/edit/' . $item['id_order']) . '" class="btn btn-primary btn-sm mt-1"><i class="fa fa-edit"></i> Edit</a> 
                                             ' . $delete . '
                                        </div>';

               $role = session()->get('role');
               if ($role == "PIC RELASI") {
                    $aksi = '<div class="text-center">
                                             <a href="' . base_url('admin/order/detail/' . $item['id_order']) . '" class="btn btn-success btn-sm mt-1">
                                                  <i class="fa fa-share"></i> Detail
                                             </a>
                                        </div>';
               }

               $data[$index]['no'] = $no++;
               $data[$index]['aksi'] = $aksi;
          }
          return $this->response->setJSON($data);
     }
     public function saveOrder()
     {
          $validate = $this->validate([
               'date' => 'required',
               'no_ref' => 'required',
               'layanan' => 'required',
               'idBillto' => 'required',
               'idPengirim' => 'required',
               'idPenerima' => 'required',
          ]);

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }
          $bln = getRomawiBulan();
          $no = $this->orderModel->getNoOrder();
          $tahun = date('Y');
          $no_order = $no . "/RHL/" . $bln . "/" . $tahun;

          $data = [
               'tanggal_order' => $this->request->getPost('date'),
               'no_order'      => $no_order,
               'no_ref'        => $this->request->getPost('no_ref') . "/" . date('m') . "/" . date('Y'),
               'id_billto'  => $this->request->getPost('idBillto'),
               'id_pelanggan'  => $this->request->getPost('idPengirim'),
               'id_penerima'   => $this->request->getPost('idPenerima'),
               'id_layanan'    => $this->request->getPost('layanan'),
          ];

          // Insert data ke database
          if ($this->orderModel->save($data)) {
               $lastId = $this->orderModel->insertID();

               return redirect()->to('/admin/order/barang/' . $lastId)->with('success', 'Insert Detail Order.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }
     public function editOrder()
     {
          $id = $this->request->getPost('id_order');
          $validate = $this->validate([
               'date' => 'required',
               'no_ref' => 'required',
               'layanan' => 'required',
               'idBillto' => 'required',
               'idPengirim' => 'required',
               'idPenerima' => 'required',
          ]);

          if (!$validate) {
               return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
          }

          $data = [
               'tanggal_order' => $this->request->getPost('date'),
               'no_ref'        => $this->request->getPost('no_ref') . "/" . date('m') . "/" . date('Y'),
               'id_billto'  => $this->request->getPost('idBillto'),
               'id_pelanggan'  => $this->request->getPost('idPengirim'),
               'id_penerima'   => $this->request->getPost('idPenerima'),
               'id_layanan'    => $this->request->getPost('layanan'),
          ];

          // Insert data ke database
          if ($this->orderModel->update($id, $data)) {
               return redirect()->to('/admin/order/all')->with('success', 'Data berhasil diubah.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
          }
     }
     public function getDetailOrder($id)
     {
          $data = $this->detailOrderModel->getDetailOrders($id);
          foreach ($data as $index => $item) {
               $data[$index]['no'] = $index + 1;
          }
          return $this->response->setJSON($data);
     }
     public function add_detail_order()
     {
          if ($this->request->getPost('berat') <= 0.5) {
               $berat  = 1;
          } else {
               $berat = $this->request->getPost('berat');
          }

          $data = [
               'id_order' => $this->request->getPost('id_order'),
               'id_barang' => $this->request->getPost('name'),
               'jumlah' => $this->request->getPost('jumlah'),
               'berat' => $berat,
               'panjang' => $this->request->getPost('panjang'),
               'lebar' => $this->request->getPost('lebar'),
               'tinggi' => $this->request->getPost('tinggi'),
               'volume' => $this->request->getPost('panjang') * $this->request->getPost('lebar') * $this->request->getPost('tinggi')
          ];
          $simpan = $this->detailOrderModel->save($data);

          $id_detail = $this->detailOrderModel->insertID();

          if ($this->request->getPost('packing') == 1) {
               $data = [
                    'id_detail_order' => $id_detail,
                    'id_biaya' => 9
               ];
               $this->detailOrderBiayaModel->save($data);
          }
          if ($simpan) {
               echo 'ok';
          } else {
               echo 'no ok';
          }
     }
     public function update_detail_order()
     {
          $id_detail = $this->request->getPost('id_detail');
          $data = [
               'id_detail_order' => $id_detail,
               'id_biaya' => 9
          ];
          $check = $this->detailOrderBiayaModel->where('id_detail_order', $id_detail)->first();

          if (empty($check) && $this->request->getPost('packing') == 1) {
               $this->detailOrderBiayaModel->save($data);
          }

          if ($check && $this->request->getPost('packing') == 0) {
               $this->detailOrderBiayaModel->where('id_detail_order', $id_detail)->where('id_biaya', 9)->delete();
          }

          if ($this->request->getPost('berat') <= 0.5) {
               $berat  = 1;
          } else {
               $berat = $this->request->getPost('berat');
          }

          $data = [
               'id_order' => $this->request->getPost('id_order'),
               'id_barang' => $this->request->getPost('name'),
               'jumlah' => $this->request->getPost('jumlah'),
               'berat' => $berat,
               'panjang' => $this->request->getPost('panjang'),
               'lebar' => $this->request->getPost('lebar'),
               'tinggi' => $this->request->getPost('tinggi'),
               'volume' => $this->request->getPost('panjang') * $this->request->getPost('lebar') * $this->request->getPost('tinggi')
          ];
          $simpan = $this->detailOrderModel->update($id_detail, $data);
          if ($simpan) {
               echo 'ok';
          } else {
               echo 'no ok';
          }
     }
     public function delete_detail_order()
     {
          $id = $this->request->getPost('id');
          $this->detailOrderModel->delete($id);
          $this->detailOrderBiayaModel->where('id_detail_order', $id)->where('id_biaya', 9)->delete();
          echo 'ok';
     }
     public function deleteOrder($id)
     {
          $this->orderModel->delete($id);
          $this->detailOrderModel->where('id_order', $id)->delete();
          return redirect()->to('/admin/order/all')->with('success', 'Data berhasil dihapus.');
     }
}

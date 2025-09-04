<?php

namespace App\Controllers;

use App\Models\PengirimanModel;
use App\Models\PengambilanModel;
use App\Models\BiayaTambahanModel;
use App\Models\ShipmentStatusLogModel;
use App\Models\TransactionExtraChargeModel;
use CodeIgniter\RESTful\ResourceController;

class PengirimanController extends ResourceController
{
     protected $pengirimanModel;
     protected $pengambilanModel;
     protected $extraCharge;
     protected $biayaTambahan;
     protected $statusLog;

     public function __construct()
     {
          $this->pengirimanModel = new PengirimanModel();
          $this->pengambilanModel = new PengambilanModel();
          $this->extraCharge = new TransactionExtraChargeModel();
          $this->biayaTambahan = new BiayaTambahanModel();
          $this->statusLog = new ShipmentStatusLogModel();
     }

     public function index()
     {
          $data = $this->pengirimanModel->findAll();
          return $this->respond($data);
     }

     public function show($id = null)
     {
          $pengiriman = $this->pengirimanModel->getPengirimanId($id);
          $data = [
               'title' => 'Status Pengiriman',
               'pengiriman' => $pengiriman,
          ];
          echo view('transaksi/pengiriman/status', $data);
     }
     public function create()
     {
          $id_pengiriman = $this->request->getPost('id_pengiriman');

          /*
          1. Update tanggal dan jam pengambilan
          */
          $id_pengambilan = $this->request->getPost('id_pengambilan');
          $data_pengambilan = [
               'tanggal_ambil' => $this->request->getPost('tanggal_ambil'),
               'waktu_ambil' => $this->request->getPost('jam_ambil'),
          ];
          $update = $this->pengambilanModel->update_shipment($id_pengambilan, $data_pengambilan);
          /*
          2. Update tanggal dan jam pengiriman
          */
          $data_pengiriman = [
               'dto' => $this->request->getPost('dto'),
               'tanggal_kirim' => $this->request->getPost('tanggal_kirim'),
          ];
          if (!empty($this->request->getPost('dto'))) {
               $data_pengiriman['tanggal_terima'] =  $this->request->getPost('tanggal_terima');
               $data_pengiriman['waktu_terima'] =  $this->request->getPost('jam_terima');
               $data_pengiriman['status'] = 'Terkirim';

               $dataLog = [
                    'shipment_id' => $id_pengiriman,
                    'status_id' => 5, // Terkirim
                    'changed_at' => $data_pengiriman['tanggal_terima'] . ' ' . $data_pengiriman['waktu_terima'],
               ];

               // cek log sudah ada belum
               $cekLog = $this->statusLog->where('shipment_id', $id_pengiriman)->where('status_id', 5)->first();
               if ($cekLog) {
                    $this->statusLog->update($cekLog['log_id'], $dataLog);
               } else {
                    $this->statusLog->insert($dataLog);
               }
          }

          $update = $this->pengirimanModel->update_shipment($id_pengiriman, $data_pengiriman);
          return redirect()->to('admin/pengiriman')->with('success', 'Data berhasil disimpan.');
     }

     // public function updatePengiriman($orderData, $orderDetails)
     // {
     //      $db = \Config\Database::connect();
     //      $db->transStart();

     //      // Insert ke tabel orders
     //      $db->table('orders')->insert($orderData);
     //      $orderId = $db->insertID(); // Ambil ID order yang baru saja dibuat

     //      // Insert ke tabel order_details
     //      foreach ($orderDetails as &$detail) {
     //           $detail['order_id'] = $orderId;
     //           $db->table('order_details')->insert($detail);
     //      }

     //      $db->transComplete();

     //      return $db->transStatus(); // Cek apakah transaksi berhasil atau tidak
     // }

     public function getPengiriman()
     {
          $date_start = $this->request->getGet('date_start');
          $date_end = $this->request->getGet('date_end');
          $pengirim = $this->request->getGet('pengirim');
          $penerima = $this->request->getGet('penerima');
          $layanan = $this->request->getGet('layanan');
          $performance = $this->request->getGet('performance');
          $type = $this->request->getGet('type');

          $data = $this->pengirimanModel->getPengiriman($date_start, $date_end, $pengirim, $penerima, $layanan, $performance);

          $role = session()->get('role');
          $isAdmin = session()->get('admin');

          if($role == 'PIC RELASI' || $isAdmin == 'N'){
               if ($date_start < '2025-08-01' && $date_end < '2025-08-31') {
                    $data = $this->pengirimanModel->getPengirimanLama($date_start, $date_end, $pengirim, $penerima, $layanan, $performance);
               }
          }

          $no = 1;
          foreach ($data as $index => $item) {
               $data[$index]['no'] = $no++;
               $data[$index]['surat_jalan'] = $item['no_surat_jalan'];

               if($type != 'report'){
                    $sj = $item['no_surat_jalan'];
                    if ($item['in_invoice'] == 1) {
                         $sj = '<span class="text-primary" title="' . $item['no_invoice'] . '">' . $item['no_surat_jalan'] . '</span>';
                    }
                    // $data[$index]['no'] = $no++;
                    $data[$index]['surat_jalan'] = $sj;
                    $data[$index]['dto'] = $item['dto'] . '<br>' . $item['tanggal_terima'] . '<br>' . $item['waktu_terima'];
                    $data[$index]['date'] = date_format(date_create($item['tanggal_kirim']), "d-m-Y");
                    $data[$index]['aksi'] = '<div class="btn-group dropleft">
                                                  <a href="#" data-toggle="dropdown" aria-expanded="false">
                                                       <i class="fa fa-ellipsis-v fa-2x" aria-hidden="true"></i>
                                                  </a>
                                                  <div class="dropdown-menu">
                                                       <a class="dropdown-item" href="' . base_url('delivery-status/' . $item['id_pengiriman']) . '"><i class="fas fa-edit mr-2"></i>Update Status</a>
                                                       <a class="dropdown-item" href="' . base_url('pengiriman/additional_cost/' . $item['id_pengiriman']) . '"><i class="fas fa-dollar-sign mr-2"></i> Biaya Tambahan</a>
                                                       <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalAsuransi" data-insurance="' . $item['insurance'] . '" data-id="' . $item['id_pengiriman'] . '">
                                                            <i class="fas fa-money-check mr-2"></i>Asuransi
                                                       </a>
                                                       <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalSurcharge" data-surcharge="' . $item['surcharge'] . '" data-id="' . $item['id_pengiriman'] . '">
                                                            <i class="fas fa-percent mr-2"></i>Surcharge
                                                       </a>
                                                       <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalDetail" data-id-order="' . $item['id_order'] . '" data-divider="' . $item['divider'] . '" data-minimum="' . $item['minimum'] . '" data-id="' . $item['id_pengiriman'] . '">
                                                            <i class="fas fa-info mr-2"></i>Detail
                                                       </a>
                                                  </div>
                                             </div>
                                        ';
               }
          }
          return $this->response->setJSON($data);
     }
     public function getPengirimanToInvoice($id)
     {
          $data = $this->pengirimanModel->getPengirimanToInvoice($id);
          // dd($data);
          $no = 1;
          foreach ($data as $index => $item) {
               $data[$index]['no'] = $no++;
               $data[$index]['date'] = date_format(date_create($item['tanggal_kirim']), "d-m-Y");
          }
          return $this->response->setJSON($data);
     }
     public function updateRemark($id)
     {
          // Ambil data dari request
          $remark = $this->request->getPost('remark');
          $update = $this->pengirimanModel->update($id, ['remark' => $remark]);
          if ($update) {
               $result = [
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
               ];
          } else {
               $result = [
                    'status' => 'error',
                    'message' => 'Data gagal diupdate'
               ];
          }

          return $this->response->setJSON($result);
     }
     public function updateInsurance($id)
     {
          // Ambil data dari request
          $insurance = intval(str_replace(",", "", $this->request->getPost(index: 'insurance')));
          $update = $this->pengirimanModel->update($id, ['insurance' => $insurance]);
          if ($update) {
               $result = [
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
               ];
          } else {
               $result = [
                    'status' => 'error',
                    'message' => 'Data gagal diupdate'
               ];
          }

          return $this->response->setJSON($result);
     }
     public function updateSurcharge($id)
     {
          // Ambil data dari request
          $surcharge = intval(str_replace(",", "", $this->request->getPost(index: 'surcharge')));
          $update = $this->pengirimanModel->update($id, ['surcharge' => $surcharge]);
          if ($update) {
               $result = [
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate'
               ];
          } else {
               $result = [
                    'status' => 'error',
                    'message' => 'Data gagal diupdate'
               ];
          }

          return $this->response->setJSON($result);
     }
     public function additional_cost($id = null)
     {
          $db = \Config\Database::connect();
          $query = $db->table('view_pengiriman_summary')
               ->where('id_pengiriman', $id)
               ->get();
          $pengirimanSummary = $query->getResultArray();

          // echo "<pre>";
          // print_r($pengirimanSummary);
          // echo "</pre>";

          // die;


          $pengiriman = $this->pengirimanModel->getPengirimanId($id);
          $extraCharge = $this->extraCharge->extraCharge($id);
          $biayaTambahan = $this->biayaTambahan->findAll();
          $data = [
               'title' => 'Biaya Tambahan',
               'pengiriman' => $pengiriman,
               'extraCharge' => $extraCharge,
               'pengirimanSummary' => $pengirimanSummary,
               'biayaTambahan' => $biayaTambahan
          ];
          echo view('transaksi/pengiriman/additional_cost', $data);
     }
     public function add_extra_cost()
     {
          $biaya_id = $this->request->getPost('biaya_id');
          $tipe_tagih = $this->request->getPost('tipe_tagih');
          $nominal = str_replace(".", "", $this->request->getPost('nominal'));
          $pengiriman_id = $this->request->getPost('id_pengiriman');
          $berat = $this->request->getPost('berat');
          $biaya_kirim = $this->request->getPost('biaya_kirim');

          if ($tipe_tagih == "per_kg") {
               $nominal = $nominal * $berat;
          } elseif ($tipe_tagih == "flat") {
               $nominal = $nominal;
          } elseif ($tipe_tagih == "percent") {
               $nominal = $biaya_kirim * $nominal / 100;
          }

          // dd($this->request->getPost());
          $data = [
               'pengiriman_id' => $pengiriman_id,
               'biaya_id' => $biaya_id,
               'charge_value' => $nominal,
          ];
          $this->extraCharge->insert($data);
          return redirect()->to(base_url('pengiriman/additional_cost/' . $pengiriman_id));
     }
     public function delete_extra_cost($id, $pengiriman_id)
     {
          $this->extraCharge->delete($id);
          return redirect()->to(base_url('pengiriman/additional_cost/' . $pengiriman_id));
     }
}

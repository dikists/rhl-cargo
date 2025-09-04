<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\JournalModel;
use App\Models\ReceivableModel;
use App\Models\DetailInvoiceModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\TransactionExtraChargeModel;
use PHPUnit\Util\Json;

class DetailInvoiceController extends BaseController
{
     use ResponseTrait;
     protected $detailInvoiceModel;
     protected $invoiceModel;
     protected $receivableModel;
     protected $JournalModel;
     protected $addCost;

     public function __construct()
     {
          $this->detailInvoiceModel = new DetailInvoiceModel();
          $this->invoiceModel = new InvoiceModel();
          $this->receivableModel = new ReceivableModel();
          $this->JournalModel = new JournalModel();
          $this->addCost = new TransactionExtraChargeModel();
     }

     // Insert Data
     public function insert()
     {
          $invoiceId    = $this->request->getPost('id_invoice');
          $pengirimanId = $this->request->getPost('id_pengiriman');

          $data = [
               'invoice_id'    => $invoiceId,
               'pengiriman_id' => $pengirimanId,
               'created_at'    => date('Y-m-d H:i:s'),
               'updated_at'    => date('Y-m-d H:i:s')
          ];

          $insert_detail = $this->detailInvoiceModel->insert($data);

          /**
           * jika berhasil insert detail invoice, maka update total amount invoice
           */
          if ($insert_detail) {
               // GET DETAIL
               $get_detail = $this->getByInvoice($invoiceId);
               if ($get_detail instanceof \CodeIgniter\HTTP\Response) {
                    $get_detail = json_decode($get_detail->getBody(), true);
               }

               $total = 0;
               $totalCost = 0;

               foreach ($get_detail as $index => $item) {
                    /** Biaya Lain */
                    $biaya_lain_arr_total = [];
                    $extraCharge = $this->addCost->extraCharge($item['id_pengiriman']);
                    foreach ($extraCharge as $extra) {
                         $biaya_lain_arr_total[] = $extra['charge_value'];
                    }
                    $biaya_lain_total = array_sum($biaya_lain_arr_total);

                    $berat = isset($item['berat']) ? (int)$item['berat'] : 0;
                    $price = isset($item['price']) ? (int)$item['price'] : 0;
                    $biaya_packing = isset($item['biaya_packing']) ? (int)$item['biaya_packing'] : 0;
                    $surcharge = isset($item['surcharge']) ? (int)$item['surcharge'] : 0;
                    $insurance = isset($item['insurance']) ? (int)$item['insurance'] : 0;

                    $total = $berat * $price + $biaya_packing + $surcharge + $insurance + $biaya_lain_total;
                    if ($item['bill_type'] == 'flat') {
                         $total = $price + $biaya_lain_total;
                    }
                    $totalCost += $total;
               }
               $updateAmount = $this->updateAmount($invoiceId, $totalCost);
               if ($updateAmount) {
                    return $this->respond(['success' => true, 'message' => 'Total amount updated']);
               }
          }

          return $this->fail('Gagal insert detail invoice', 500);
     }

     public function updateAmount($id, $amount)
     {
          if (!is_numeric($amount) || !$id) {
               return $this->fail('ID atau Amount tidak valid', 400);
          }

          $noInvoice = $this->invoiceModel->find($id);
          $receivable = $this->receivableModel->where('invoice_number', $noInvoice['invoice_number'])->first();
          $jurnal = $this->JournalModel->where('reference', $noInvoice['invoice_number'])->findAll();

          foreach ($jurnal as $item) {
               /**  
                * PIUTANG USAHA = 2, 
                * PENDAPATAN PENJUALAN = 3 
                */

               if ($item['journal_account_id'] == 2) {
                    $updateData = ['debit' => $amount];
               } elseif ($item['journal_account_id'] == 3) {
                    $updateData = ['credit' => $amount];
               } else {
                    continue;
               }

               $this->JournalModel->update($item['id'], $updateData);
          }

          $data = ['total_amount' => $amount];

          // Debug untuk memastikan update berjalan
          $update = $this->receivableModel->update($receivable['id'], $data);
          $update = $this->invoiceModel->update($id, $data);

          if (!$update) {
               return false;
          }

          return true;
     }

     public function delete($id)
     {
          $invoiceId = $this->request->getPost('invoice_id');
          $delete = $this->detailInvoiceModel->delete($id);

          /**
           * jika berhasil hapus detail invoice, maka update total amount invoice
           */
          if ($delete) {
               // GET DETAIL
               $get_detail = $this->getByInvoice($invoiceId);
               if ($get_detail instanceof \CodeIgniter\HTTP\Response) {
                    $get_detail = json_decode($get_detail->getBody(), true);
               }

               $total = 0;
               $totalCost = 0;

               foreach ($get_detail as $index => $item) {
                    /** Biaya Lain */
                    $biaya_lain_arr_total = [];
                    $extraCharge = $this->addCost->extraCharge($item['id_pengiriman']);
                    foreach ($extraCharge as $extra) {
                         $biaya_lain_arr_total[] = $extra['charge_value'];
                    }
                    $biaya_lain_total = array_sum($biaya_lain_arr_total);

                    $berat = isset($item['berat']) ? (int)$item['berat'] : 0;
                    $price = isset($item['price']) ? (int)$item['price'] : 0;
                    $biaya_packing = isset($item['biaya_packing']) ? (int)$item['biaya_packing'] : 0;
                    $surcharge = isset($item['surcharge']) ? (int)$item['surcharge'] : 0;
                    $insurance = isset($item['insurance']) ? (int)$item['insurance'] : 0;

                    $total = $berat * $price + $biaya_packing + $surcharge + $insurance + $biaya_lain_total;
                    if ($item['bill_type'] == 'flat') {
                         $total = $price + $biaya_lain_total;
                    }
                    $totalCost += $total;
               }
               $updateAmount = $this->updateAmount($invoiceId, $totalCost);
               if ($updateAmount) {
                    return $this->respond(['success' => true, 'message' => 'Total amount updated']);
               }
          }
          return $this->respond(['success' => false, 'message' => 'data gagal dihapus']);
     }

     public function getByInvoice($invoiceId)
     {
          $data = $this->detailInvoiceModel->getByInvoice($invoiceId);

          // echo "<pre>";
          // print_r($data);
          // echo "</pre>";
          $no = 1;
          foreach ($data as $index => $item) {
               if ($item['pengiriman_id'] == null) {
                    continue;
               }
               $extra = json_decode($item['extra_charges'], true);
               $total_biaya_lain_arr = [];

               // Format jadi string untuk ditampilkan
               $formattedCharges = '-';
               if (!empty($extra) && is_array($extra)) {
                    $lines = [];
                    foreach ($extra as $charge) {
                         $lines[] = $charge['jenis_biaya'] . ' : ' . formatRupiah($charge['charge_value']);
                         $total_biaya_lain_arr[] = $charge['charge_value'];
                    }
                    $formattedCharges = implode('<br>', $lines); // Bisa juga pakai "\n" jika plaintext
               }
               $total_biaya_lain = array_sum($total_biaya_lain_arr);

               $data[$index]['no'] = $no++;
               $data[$index]['date'] = date_format(date_create($item['tanggal_kirim']), "d-m-Y");
               $data[$index]['berat'] = round($item['berat'], 2);
               $data[$index]['extra_charge'] = $formattedCharges;
               $data[$index]['total_biaya_lain'] = $total_biaya_lain;
               $data[$index]['aksi'] = '<button class="btn btn-danger btn-sm m-1 deleteDetailInvoice" data-id="' . $item['id_detail_invoice'] . '" data-invoice_id="' . $invoiceId . '"><i class="fa fa-trash"></i></button>';
          }
          return $this->response->setJSON($data);
     }
}

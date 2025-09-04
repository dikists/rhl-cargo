<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\ClauseModel;
use App\Models\InvoiceModel;
use App\Models\PelangganModel;
use App\Models\ReceivableModel;
use App\Services\JurnalService;
use App\Models\BankAccountModel;
use App\Models\ClauseDetailModel;
use App\Models\OfferDetailsModel;
use CodeIgniter\RESTful\ResourceController;

class OfferController extends ResourceController
{
     protected $invoiceModel;
     protected $pelangganModel;
     protected $offerModel;
     protected $clauseModel;
     protected $clauseDetailModel;
     protected $offerDetailsModel;
     protected $rekeningModel;
     protected $receivableModel;
     protected $jurnalService;

     public function __construct()
     {
          $this->invoiceModel = new InvoiceModel();
          $this->pelangganModel = new PelangganModel();
          $this->rekeningModel = new BankAccountModel();
          $this->offerModel = new OfferModel();
          $this->offerDetailsModel = new OfferDetailsModel();
          $this->clauseModel = new ClauseModel();
          $this->clauseDetailModel = new ClauseDetailModel();
          $this->receivableModel = new ReceivableModel();
          $this->jurnalService = new JurnalService();
     }

     public function index()
     {
          //  'rekening' => $this->rekeningModel->getRekening(),
          $data = [
               'title' => 'Daftar Penawaran',
               'offers' => $this->offerModel->getOffer(),
               'rekening' => $this->rekeningModel->getRekening()
          ];
          echo view('transaksi/penawaran/index', $data);
     }

     public function show($id = null)
     {
          $offer = $this->offerModel->find($id);
          if (!$offer) {
               return $this->failNotFound("Data tidak ditemukan");
          }
          return $this->respond($offer);
     }

     /**
      * Create a new offer.
      *
      * This method handles the creation of a new offer by retrieving the POST data
      * from the request and attempting to insert it into the database using the OfferModel.
      * If the insertion is successful, it returns a response indicating the offer was
      * successfully created along with the created data. If there are validation errors,
      * it returns a response with the validation error messages.
      */
     public function create()
     {
          $clause = $this->request->getPost('offer_clause_desc');
          $desc = $this->get_desc($clause);

          $data = [
               'pelanggan_id' => $this->request->getPost('pelanggan_id'),
               'offer_date' => $this->request->getPost('offer_date'),
               'offer_text_title' => $this->request->getPost('offer_text_title'),
               'offer_text_opening' => $this->request->getPost('offer_text_opening'),
               'offer_clause_desc' => $desc
          ];

          $insert = $this->offerModel->insert($data);
          $last_id = $this->offerModel->getInsertID();

          if ($insert) {
               return redirect()->to('admin/offer-details/' . $last_id)->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
          }
     }

     private function get_desc($id)
     {
          $data = $this->clauseModel->find($id);
          $list_clause = explode('|', $data['clause_desc']);

          $clause_desc = '<ul>';
          for ($i = 0; $i < count($list_clause); $i++) {
               $detail = $this->clauseDetailModel->find($list_clause[$i]);
               $clause_desc      .= '<li>' . $detail['clause_detail_desc'] . '</li>';
          }
          $clause_desc .= '</ul>';

          return $clause_desc;
     }

     public function update($id = null)
     {
          $clause = $this->request->getPost('offer_clause_desc');

          $data = [
               'pelanggan_id' => $this->request->getPost('pelanggan_id'),
               'offer_date' => $this->request->getPost('offer_date'),
               'offer_text_title' => $this->request->getPost('offer_text_title'),
               'offer_text_opening' => $this->request->getPost('offer_text_opening'),
          ];

          if (!empty($clause)) {
               $desc = $this->get_desc($clause);
               $data['offer_clause_desc'] = $desc;
          }

          $update = $this->offerModel->update($id, $data);

          if ($update) {
               return redirect()->to('admin/offer-details/' . $id)->with('success', 'Data berhasil diubah.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
          }
     }

     public function delete($id = null)
     {
          if ($this->offerModel->delete($id)) {
               return redirect()->to('admin/offers')->with('success', 'Data berhasil dihapus.');
          }
          return redirect()->back()->withInput()->with('error', 'Gagal menghapus data.');
     }
     public function offers_pdf($id = null)
     {
          // $tes = $this->offerDetailsModel->find($id);
          // var_dump($tes);
          // die;
          ini_set('max_execution_time', 300);
          helper('pdf');
          $data = [
               'offer' => $this->offerModel->getOffer($id),
               'offer_details' => $this->offerDetailsModel->getOfferDetails($id),
          ];

          $html = view('transaksi/penawaran/pdf_offers', $data);
          create_pdf($html, 'Offers_' . $id . '.pdf');
          exit;
     }

     public function generateInvoice()
     {
          $offer_id = $this->request->getPost('offer_id');
          $rtax = $this->request->getPost('rtax');
          $rtax_pph = $this->request->getPost('rtax_pph');
          $no_rekening = $this->request->getPost('no_rekening');

          $offer = $this->offerModel->getOffer($offer_id);

          $dataOffer = [
               'status' => 'D'
          ];

          $this->offerModel->update($offer_id, $dataOffer);

          $invoice_number = $offer[0]['offer_number'];
          $id_pelanggan = $offer[0]['pelanggan_id'];
          $nama_pelanggan = $offer[0]['nama_pelanggan'];
          $total_amount = $offer[0]['total_offers'];
          $issue_fdate = $offer[0]['offer_date'];
          $title = $offer[0]['offer_text_title'];

          $pelanggan = $this->pelangganModel->getPelanggan($id_pelanggan);
          $top = ($pelanggan) ? $pelanggan['top'] : 0;
          $dueDate = new \DateTime($issue_fdate);
          $dueDate->modify("+{$top} days"); // Tambah top (misalnya 30 hari)

          $data = [
               'invoice_number'        => $invoice_number,
               'id_pelanggan'          => $id_pelanggan,
               'account_id'            => $no_rekening,
               'issue_date'            => $issue_fdate,
               'due_date'              => $dueDate->format('Y-m-d'),
               'tax_invoice_number'    => '',
               'rtax_id'               => $rtax ?: null,
               'rtaxpph_id'            => $rtax_pph ?: null,
               'notes'                 => 'Dari ' . $title,
               'status'                => 'Pending',
               'total_amount'          => $total_amount,
               'source'                => 'Offer',
          ];

          $dataReceivables = [
               'customer_id' => $id_pelanggan,
               'invoice_number' => $invoice_number,
               'invoice_date' => $issue_fdate,
               'due_date' => $dueDate->format('Y-m-d'),
               'total_amount' => $total_amount,
               'description' => 'Invoice ' . $title . ' ke ' . $nama_pelanggan,
          ];

          // Insert data ke database
          $this->receivableModel->insert($dataReceivables);

          $this->jurnalService->generateJurnalPiutang();

          $insert = $this->invoiceModel->insert($data);

          if ($insert) {
               $response = [
                    'status'  => true,
                    'message' => 'Invoice berhasil dibuat',
                    'invoice_id' => $this->invoiceModel->getInsertID()
               ];
          } else {
               $response = [
                    'status'  => false,
                    'message' => 'Gagal membuat invoice'
               ];
          }

          // Kembalikan sebagai JSON
          return $this->response->setJSON($response);
     }
}

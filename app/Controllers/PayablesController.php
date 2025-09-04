<?php

namespace App\Controllers;

use App\Models\PayableModel;
use App\Models\VendorModel;
use App\Models\PayablePaymentModel;

class PayablesController extends BaseController
{
    protected $payableModel;
    protected $vendorModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->payableModel = new PayableModel();
        $this->vendorModel = new VendorModel();
        $this->paymentModel = new PayablePaymentModel();
    }

    public function index()
    {
        $data['payables'] = $this->payableModel->getWithSupplier();
        $data['supplier'] = $this->vendorModel->findAll();
        $data['title'] = 'Hutang';
        return view('hutang/index', $data);
    }

    public function hutangDataTable()
    {
        $params = [
            'date_start' => $this->request->getPost('date_start'),
            'date_end'   => $this->request->getPost('date_end'),
            'supplier'   => $this->request->getPost('supplier'),
            'status'   => $this->request->getPost('status'),
            'search'     => $this->request->getPost('search')['value'] ?? '',
            'length'     => (int) $this->request->getPost('length'),
            'start'      => (int) $this->request->getPost('start'),
        ];

        $result = $this->payableModel->getDatatables($params);
        $totalRecords = $result['recordsFiltered'];

        $data = [];
        $no = 1;
        $totalAmount = $totalPaid = 0;
        foreach ($result['data'] as $row) {
            $totalAmount += $row->total_amount;
            $totalPaid += $row->paid_amount;
            $sisa = $row->total_amount - $row->paid_amount;
            $status = '<span class="badge bg-danger text-white">Belum Bayar</span>';
            $aksi = "";

            if ($row->status == 'paid') {
                $status = '<span class="badge bg-success text-white">Lunas</span>';
                $aksi = "";
            } else if ($row->status == 'partial') {
                $status = '<span class="badge bg-warning text-white">Sebagian</span>';
                $aksi = " <a href='" . base_url('admin/hutang/pay/' . $row->id) . "' class='btn btn-sm btn-outline-primary'>Bayar</a>";
            } else if ($row->status == 'unpaid') {
                $status = '<span class="badge bg-danger text-white">Belum Lunas</span>';
                $aksi = " <a href='" . base_url('admin/hutang/pay/' . $row->id) . "' class='btn btn-sm btn-outline-primary'>Bayar</a>";
            }

            $data[] = [
                $no++,
                $row->supplier_name,
                $row->invoice_number,
                date('d-m-Y', strtotime($row->invoice_date)),
                $row->due_date,
                formatRupiah($row->total_amount),
                formatRupiah($row->paid_amount),
                formatRupiah($sisa),
                $status,
                $aksi
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data,
            "total_amount" => $totalAmount,
            "total_paid"   => $totalPaid,
            "total_sisa"   => $totalAmount - $totalPaid,
        ]);
    }

    public function create()
    {
        $data['suppliers'] = $this->vendorModel->findAll();
        $data['title'] = 'Tambah Hutang';
        return view('hutang/create', $data);
    }

    public function store()
    {
        $this->payableModel->save([
            'supplier_id'    => $this->request->getPost('supplier_id'),
            'invoice_number' => $this->request->getPost('invoice_number'),
            'invoice_date'   => $this->request->getPost('invoice_date'),
            'due_date'       => $this->request->getPost('due_date'),
            'total_amount'   => $this->request->getPost('total_amount'),
            'description'    => $this->request->getPost('description'),
        ]);

        $this->generateJurnalHutang();

        return redirect()->to('/admin/hutang');
    }

    public function generateJurnalHutang()
    {
        $payables = $this->payableModel
            ->where('deleted_at', null)
            ->where('is_journaled', 0)
            ->getWithSupplier();

        // echo "<pre>";
        // print_r($payables);
        // echo "</pre>";
        // die;

        $jurnalModel = new \App\Models\JournalModel();

        foreach ($payables as $item) {

            /** 4 = Beban Operasional, 6 = Hutang Usaha */

            // Jika belum ada, baru insert
            $jurnalModel->insertBatch([
                [
                    'journal_date' => $item['invoice_date'],
                    'reference' => $item['invoice_number'],
                    'journal_account_id' => 4, // Beban Operasional
                    'debit' => $item['total_amount'],
                    'credit' => 0,
                    'description' => 'Invoice dari ' . $item['supplier_name']
                ],
                [
                    'journal_date' => $item['invoice_date'],
                    'reference' => $item['invoice_number'],
                    'journal_account_id' => 6, // Hutang Usaha
                    'debit' => 0,
                    'credit' => $item['total_amount'],
                    'description' => 'Invoice dari ' . $item['supplier_name']
                ]
            ]);

            $this->payableModel->update($item['id'], ['is_journaled' => 1]);
        }
    }

    public function pay($id)
    {
        $data['payable'] = $this->payableModel->getWithSupplier($id);
        $data['payments'] = $this->paymentModel->getPaymentsByPayable($id);
        $data['title'] = 'Pembayaran Hutang';
        return view('hutang/pay', $data);
    }

    public function storePayment($id)
    {
        $amount = $this->request->getPost('amount');
        $this->paymentModel->save([
            'payable_id'     => $id,
            'payment_date'   => $this->request->getPost('payment_date'),
            'amount'         => $amount,
            'payment_method' => $this->request->getPost('payment_method'),
            'notes'          => $this->request->getPost('notes'),
        ]);

        // Update total paid dan status
        $payable = $this->payableModel->find($id);
        $totalPaid = $payable['paid_amount'] + $amount;
        $status = $totalPaid >= $payable['total_amount'] ? 'paid' : 'partial';

        $this->payableModel->update($id, [
            'paid_amount' => $totalPaid,
            'status'      => $status
        ]);

        $this->generateJurnalPembayaranHutang();

        return redirect()->to("/admin/hutang");
    }

    public function generateJurnalPembayaranHutang()
    {
        $payments = $this->paymentModel->getAllWithRelations();

        $jurnalModel = new \App\Models\JournalModel();

        foreach ($payments as $item) {
            /** 6 = Hutang Usaha, 1 = Kas/Bank */
            $jurnalModel->insertBatch([
                [
                    'journal_date' => $item['payment_date'],
                    'reference' => 'PAY/' . $item['invoice_number'],
                    'journal_account_id' => 6, // Hutang Usaha
                    'debit' => $item['amount'],
                    'credit' => 0,
                    'description' => 'Bayar hutang ' . $item['vendor_name']
                ],
                [
                    'journal_date' => $item['payment_date'],
                    'reference' => 'PAY/' . $item['invoice_number'],
                    'journal_account_id' => 1, // Kas/Bank
                    'debit' => 0,
                    'credit' => $item['amount'],
                    'description' => 'Bayar hutang ' . $item['vendor_name']
                ]
            ]);

            $this->paymentModel->update($item['id'], ['is_journaled' => 1]);
        }
    }
}

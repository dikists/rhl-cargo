<?php

namespace App\Controllers;

use App\Models\ReceivableModel;
use App\Models\PelangganModel;
use App\Models\ReceivablePaymentModel;

class ReceivablesController extends BaseController
{
    protected $receivableModel;
    protected $pelangganModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->receivableModel = new ReceivableModel();
        $this->pelangganModel = new PelangganModel();
        $this->paymentModel = new ReceivablePaymentModel();
    }

    public function index()
    {
        $data['receivables'] = $this->receivableModel->getWithCustomer();
        $data['pelanggan'] = $this->pelangganModel->findAll();
        $data['title'] = 'Piutang';
        return view('piutang/index', $data);
    }

    public function piutangDataTable()
    {
        $params = [
            'date_start' => $this->request->getPost('date_start'),
            'date_end'   => $this->request->getPost('date_end'),
            'pelanggan'   => $this->request->getPost('pelanggan'),
            'status'   => $this->request->getPost('status'),
            'search'     => $this->request->getPost('search')['value'] ?? '',
            'length'     => (int) $this->request->getPost('length'),
            'start'      => (int) $this->request->getPost('start'),
        ];
        $result = $this->receivableModel->getDatatables($this->request->getPost());

        $totalRecords = $result['recordsFiltered'];
        $data = [];
        $no = 1;
        $totalAmount = $totalPaid = 0;
        foreach ($result['data'] as $row) {
            $totalAmount += $row->total_amount;
            $totalPaid += $row->paid_amount;
            $status = '<span class="badge bg-danger text-white">Belum Bayar</span>';

            if ($row->status == 'paid') {
                $status = '<span class="badge bg-success text-white">Lunas</span>';
            } else if ($row->status == 'partial') {
                $status = '<span class="badge bg-warning text-white">Sebagian</span>';
            } else if ($row->status == 'unpaid') {
                $status = '<span class="badge bg-danger text-white">Belum Lunas</span>';
            }
            $data[] = [
                $no++,
                $row->invoice_number,
                $row->customer_name,
                date('d-m-Y', strtotime($row->invoice_date)),
                formatRupiah($row->total_amount),
                formatRupiah($row->paid_amount),
                $status
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
        $data['customers'] = $this->pelangganModel->findAll();
        $data['title'] = 'Tambah Piutang';
        return view('piutang/create', $data);
    }

    public function store()
    {
        $this->receivableModel->save([
            'customer_id'     => $this->request->getPost('customer_id'),
            'invoice_number'  => $this->request->getPost('invoice_number'),
            'invoice_date'    => $this->request->getPost('invoice_date'),
            'due_date'        => $this->request->getPost('due_date'),
            'total_amount'    => $this->request->getPost('total_amount'),
            'description'     => $this->request->getPost('description'),
        ]);

        return redirect()->to('/receivables');
    }

    public function pay($id)
    {
        $data['receivable'] = $this->receivableModel->getWithCustomer($id);
        $data['payments']   = $this->paymentModel->getPaymentsByReceivable($id);
        return view('receivables/pay', $data);
    }

    public function storePayment($id)
    {
        $amount = $this->request->getPost('amount');
        $this->paymentModel->save([
            'receivable_id'  => $id,
            'payment_date'   => $this->request->getPost('payment_date'),
            'amount'         => $amount,
            'payment_method' => $this->request->getPost('payment_method'),
            'notes'          => $this->request->getPost('notes'),
        ]);

        // Update total paid dan status
        $receivable = $this->receivableModel->find($id);
        $totalPaid = $receivable['paid_amount'] + $amount;
        $status = $totalPaid >= $receivable['total_amount'] ? 'paid' : 'partial';

        $this->receivableModel->update($id, [
            'paid_amount' => $totalPaid,
            'status'      => $status
        ]);

        return redirect()->to("/receivables/pay/$id");
    }
}

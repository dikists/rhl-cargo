<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceivablePaymentModel extends Model
{
    protected $table      = 'receivable_payments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'receivable_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
        'is_journaled'
    ];
    protected $useTimestamps = true;

    public function getPaymentsByReceivable($receivable_id)
    {
        return $this->where('receivable_id', $receivable_id)->findAll();
    }

    public function getReceivablePaymentJournal()
    {
        return $this->select('receivable_payments.*, receivables.invoice_number, tb_pelanggan.nama_pelanggan')
            ->join('receivables', 'receivables.id = receivable_payments.receivable_id', 'left')
            ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = receivables.customer_id', 'left')
            ->where('receivable_payments.is_journaled', 0)
            ->findAll();
    }
}

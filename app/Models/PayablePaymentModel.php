<?php

namespace App\Models;

use CodeIgniter\Model;

class PayablePaymentModel extends Model
{
    protected $table      = 'payable_payments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'payable_id',
        'payment_date',
        'amount',
        'payment_method',
        'notes',
        'is_journaled'
    ];
    protected $useTimestamps = true;

    public function getPaymentsByPayable($payable_id)
    {
        return $this->where('payable_id', $payable_id)->findAll();
    }

    public function getAllWithRelations()
    {
        return $this->select('payable_payments.*, payables.id as payables_id, payables.invoice_number, tb_vendor.vendor_name')
            ->join('payables', 'payables.id = payable_payments.payable_id', 'left')
            ->join('tb_vendor', 'tb_vendor.vendor_id = payables.supplier_id', 'left')
            ->where('payable_payments.is_journaled', 0)
            ->findAll();
    }
}

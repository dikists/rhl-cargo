<?php

namespace App\Models;

use CodeIgniter\Model;

class PayableModel extends Model
{
    protected $table      = 'payables';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'total_amount',
        'paid_amount',
        'status',
        'description',
        'is_journaled'
    ];
    protected $useTimestamps = true;

    public function getDatatables($params)
    {
        $builder = $this->select('payables.*, tb_vendor.vendor_name AS supplier_name')
            ->join('tb_vendor', 'tb_vendor.vendor_id = payables.supplier_id');

        // Tanggal filter
        if (!empty($params['date_start']) && !empty($params['date_end'])) {
            $builder->where('payables.invoice_date >=', $params['date_start']);
            $builder->where('payables.invoice_date <=', $params['date_end']);
        }

        // Supplier filter
        if (!empty($params['supplier'])) {
            $builder->where('payables.supplier_id', $params['supplier']);
        }
        // Status filter
        if (!empty($params['status'])) {

            if ($params['status'] == 'paid') {
                $builder->where('payables.status', $params['status']);
            }else{
                $builder->where('payables.status !=', 'paid');
            }
        }

        // Search
        if (!empty($params['search'])) {
            $builder->groupStart()
                ->like('payables.invoice_number', $params['search'])
                ->orLike('payables.total_amount', $params['search'])
                ->orLike('tb_vendor.vendor_name', $params['search'])
                ->groupEnd();
        }

        // Count total
        $totalFiltered = $builder->countAllResults(false);

        $builder->orderBy('payables.id', 'DESC');

        // Paging
        if (isset($params['length']) && $params['length'] != -1) {
            $builder->limit($params['length'], $params['start']);
        }

        // echo $builder->getCompiledSelect();
        // exit;

        $query = $builder->get();

        return [
            'data' => $query->getResult(),
            'recordsFiltered' => $totalFiltered
        ];
    }
    public function getWithSupplier($id = null)
    {
        $builder = $this->select('payables.*, tb_vendor.vendor_name AS supplier_name')
            ->join('tb_vendor', 'tb_vendor.vendor_id = payables.supplier_id');
        if ($id) {
            return $builder->where('payables.id', $id)->first();
        }
        return $builder->findAll();
    }
}

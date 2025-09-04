<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceivableModel extends Model
{
    protected $table      = 'receivables';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'customer_id',
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

    public function getWithCustomer($id = null)
    {
        $builder = $this->select('receivables.*, tb_pelanggan.nama_pelanggan AS customer_name')
            ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = receivables.customer_id');
        if ($id) {
            return $builder->where('receivables.id', $id)->first();
        }
        return $builder->findAll();
    }
    public function getReceivableJournal()
    {
        return $this->select('receivables.*, tb_pelanggan.nama_pelanggan')
            ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = receivables.customer_id', 'left')
            ->where('receivables.is_journaled', 0)
            ->findAll();
    }

    public function getDatatables($params)
    {
        $start = (int) $params['start'];
        $length = (int) $params['length'];
        $search = $params['search']['value'] ?? '';
        
        $builder = $this->select('receivables.*, tb_pelanggan.nama_pelanggan AS customer_name')
            ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = receivables.customer_id');

        if (!empty($params['date_start']) && !empty($params['date_end'])) {
            $builder->where('receivables.invoice_date >=', $params['date_start']);
            $builder->where('receivables.invoice_date <=', $params['date_end']);
        }

        // Pelanggan filter
        if (!empty($params['pelanggan'])) {
            $builder->where('receivables.customer_id', $params['pelanggan']);
        }

        // Status filter
        if (!empty($params['status'])) {
            if ($params['status'] == 'paid') {
                $builder->where('receivables.status', $params['status']);
            } else {
                $builder->where('receivables.status !=', 'paid');
            }
        }

        // Search
        if (!empty($search)) {
            $builder->groupStart()
                ->like('receivables.invoice_number', $search)
                ->orLike('receivables.total_amount', $search)
                ->orLike('tb_pelanggan.nama_pelanggan', $search)
                ->groupEnd();
        }

        // Count total
        $totalFiltered = $builder->countAllResults(false);

        $builder->orderBy('receivables.invoice_date', 'DESC');

        // Paging
        if ( isset($length) &&  $length != -1) {
            $builder->limit($length, $start);
        }

        // echo $builder->getCompiledSelect();
        // exit;

        $query = $builder->get();
        return [
            'data' => $query->getResult(),
            'recordsFiltered' => $totalFiltered
        ];
    }
}

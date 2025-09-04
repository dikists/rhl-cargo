<?php
// app/Models/UangJalanModel.php

namespace App\Models;

use CodeIgniter\Model;

class UangJalanModel extends Model
{
    protected $table = 'uang_jalan';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true; // agar support deleted_at
    protected $allowedFields = [
        'tanggal',
        'user_id',
        'tujuan',
        'jumlah',
        'metode_pembayaran',
        'keterangan',
        'reference',
        'status',
        'is_printed',
        'approved_at'
    ];

    public function getUangJalanDatatable($params)
    {
        $start = (int) $params['start'];
        $length = (int) $params['length'];
        $search = $params['search']['value'] ?? '';

        $builder = $this->db->table('uang_jalan');
        $builder->select('uang_jalan.*, tb_user.username as driver_name');
        $builder->join('tb_user', 'tb_user.id = uang_jalan.user_id', 'left');

        $builder->where('uang_jalan.deleted_at', null);

        if (!empty($params['date_start']) && !empty($params['date_end'])) {
            $builder->where('uang_jalan.tanggal >=', $params['date_start']);
            $builder->where('uang_jalan.tanggal <=', $params['date_end']);
        }

        if (!empty($params['drivers'])) {
            $builder->where('uang_jalan.user_id', $params['drivers']);
        }

        if (!empty($params['status'])) {
            $builder->where('uang_jalan.status', $params['status']);
        }

        if (!empty($search)) {
            $builder->like('uang_jalan.tanggal', $search);
            $builder->orLike('tb_user.username', $search);
            $builder->orLike('uang_jalan.reference', $search);
            $builder->orLike('uang_jalan.jumlah', $search);
            $builder->orLike('uang_jalan.tujuan', $search);
            $builder->orLike('uang_jalan.keterangan', $search);
        }

        // Count total
        $totalFiltered = $builder->countAllResults(false);

        // Order
        // if (!empty($params['order'])) {
        //     $builder->orderBy($params['order'][0]['column'], $params['order'][0]['dir']);
        // }

        $builder->orderBy('uang_jalan.id', 'DESC');

        // Paging
        if (isset($length) &&  $length != -1) {
            $builder->limit($length, $start);
        }

        $query = $builder->get();
        return [
            'data' => $query->getResult(),
            'recordsFiltered' => $totalFiltered
        ];
    }
    public function getUangJalan($id = null)
    {
        $builder = $this->db->table('uang_jalan');
        $builder->select('uang_jalan.*, tb_user.username as driver_name');
        $builder->join('tb_user', 'tb_user.id = uang_jalan.user_id', 'left');
        $builder->where('uang_jalan.deleted_at', null);

        if ($id != null) {
            $builder->where('uang_jalan.id', $id);
        }

        $query = $builder->get();
        if ($id != null) {
            return $query->getRowArray();
        }
        return $query->getResultArray();
    }
}

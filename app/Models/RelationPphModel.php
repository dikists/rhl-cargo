<?php

namespace App\Models;

use CodeIgniter\Model;

class RelationPphModel extends Model
{
    protected $table            = 'relation_pph';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'pelanggan_id', 'category_id', 'percent', 'date_start', 'date_end',
        'description', 'status', 'created_at', 'created_by', 
        'updated_at', 'updated_by', 'deleted_at', 'deleted_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getTaxPph($id)
    {
         return $this->db->table($this->table)
              ->select('relation_pph.*, choice_list.choice_name, choice_list.id as choice_id')
              ->join('choice_list', 'choice_list.id = relation_pph.category_id', 'left')
              ->where('status', 1)
              ->where('pelanggan_id', $id)
              ->orderBy('relation_pph.id')
              ->get()
              ->getResultArray();
    }
}

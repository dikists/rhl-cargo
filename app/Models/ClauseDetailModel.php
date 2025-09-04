<?php
namespace App\Models;

use CodeIgniter\Model;

class ClauseDetailModel extends Model
{
    protected $table            = 'clause_detail';
    protected $primaryKey       = 'clause_detail_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'clause_detail_desc',
        'clause_detail_create_at',
        'clause_detail_create_by',
        'clause_detail_remove_at',
        'clause_detail_edit_at',
        'clause_detail_edit_by',
        'clause_detail_remove_by',
        'clause_detail_status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'clause_detail_create_at';
    protected $updatedField  = 'clause_detail_edit_at';
    protected $deletedField  = 'clause_detail_remove_at';

    public function getAllClauseDetails()
    {
        return $this->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ChoiceListModel extends Model
{
    protected $table      = 'choice_list';
    protected $primaryKey = 'id';
    protected $useTimestamps = true; // Untuk mengaktifkan pengisian otomatis created_at dan updated_at

    // Kolom yang diizinkan untuk diisi
    protected $allowedFields = ['choice_type', 'choice_name', 'created_at', 'updated_at', 'deleted_at'];

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    public function getChoicesByType($choice_type)
    {
        return $this->where('choice_type', $choice_type)
                    ->findAll();
    }
}

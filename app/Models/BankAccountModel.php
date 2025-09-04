<?php

namespace App\Models;

use CodeIgniter\Model;

class BankAccountModel extends Model
{
    protected $table            = 'bank_accounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    // Kolom yang diizinkan untuk diisi
    protected $allowedFields    = [
        'account_holder_name',
        'account_number',
        'bank_name',
        'account_type',
        'balance',
        'signatory',
        'status'
    ];

    // Soft delete
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    // Timestamps
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getRekening($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }
        
        return $this->find($id);
    }
}

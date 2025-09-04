<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'tb_vendor';
    protected $primaryKey = 'vendor_id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'remove_at';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'vendor_name',
        'short_name',
        'vendor_email',
        'vendor_phone',
        'vendor_group_phone',
        'vendor_address',
        'created_at',
        'updated_at',
        'remove_at'
    ];

    public function getVendor($id = false)
    {
        if ($id === false) {
            return $this->orderBy('vendor_id', 'DESC')->findAll();
        }

        return $this->orderBy('vendor_id', 'DESC')->find($id);
    }
}

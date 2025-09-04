<?php

namespace App\Models;

use CodeIgniter\Model;

class TruckModel extends Model
{
    protected $table = 'trucks';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'plate_number',
        'type',
        'brand',
        'year',
        'status',
        'created_at',
        'updated_at'
    ];

    // Auto set timestamps
    protected $useTimestamps = true; // menggunakan created_at & updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // (Opsional) Format timestamp-nya
    protected $dateFormat = 'datetime'; // atau 'date', 'int', tergantung kebutuhan
}

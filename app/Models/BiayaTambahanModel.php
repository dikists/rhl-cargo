<?php

namespace App\Models;

use CodeIgniter\Model;

class BiayaTambahanModel extends Model
{
    protected $table            = 'biaya_tambahan';
    protected $primaryKey       = 'id_biaya';
    protected $allowedFields    = ['jenis_biaya', 'nominal', 'tipe_tagih', 'tanggal_input', 'keterangan'];

    // Optional: jika memakai timestamps
    protected $useTimestamps = false;
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'tb_barang';
    protected $primaryKey = 'id_barang';

    protected $allowedFields = [
        'nama_barang',
        'berat',
        'kubikasi',
        'panjang',
        'lebar',
        'tinggi',
        'satuan'
    ];

    public function getBarang($id_barang = false)
    {
        if ($id_barang === false) {
            return $this->findAll();
        }
        
        return $this->find($id_barang);
    }
}

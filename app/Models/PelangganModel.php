<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table = 'tb_pelanggan';
    protected $primaryKey = 'id_pelanggan';

    // Kolom yang diizinkan untuk di-manipulasi
    protected $allowedFields = [
        'nama_pelanggan',
        'alamat_pelanggan',
        'kota',
        'telepon_pelanggan',
        'email',
        'password',
        'status',
        'top',
        'npwp'
    ];

    // Atur pengembalian hasil sebagai array
    protected $returnType = 'array';

    // Fungsi untuk mendapatkan pelanggan berdasarkan ID
    public function getPelanggan($id_pelanggan = null)
    {
        if ($id_pelanggan === null) {
            return $this->findAll();
        }
        return $this->find($id_pelanggan);
    }

    public function getPelanggan_new()
    {
        return $this->db->table($this->table .' tp')
            ->select('tp.*, 
                    (SELECT COUNT(*) FROM relation_tax rt WHERE rt.rtax_relation_id = tp.id_pelanggan and rt.rtax_status = "Y") as ppn, 
                    (SELECT COUNT(*) FROM relation_pph rp WHERE rp.pelanggan_id = tp.id_pelanggan and rp.deleted_at is null) as pph')
            ->get()
            ->getResultArray();
    }
}

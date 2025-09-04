<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table = 'tb_layanan';         // Nama tabel
    protected $primaryKey = 'id_layanan';    // Primary key

    protected $useSoftDeletes = true;        // Menggunakan soft delete
    protected $allowedFields = [
        'id_pelanggan',
        'id_penerima',
        'kabupaten_id',
        'kecamatan_id',
        'layanan',
        'minimum',
        'leadtime',
        'biaya_paket',
        'divider',
        'bill_type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Menentukan pengaturan timestamp otomatis
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    /**
     * Method untuk mengambil semua data layanan.
     * @return array
     */
    public function getAllLayanan()
    {
        return $this->where('deleted_at', null)->findAll();
    }
    public function getLayanan($id = false, $pengirim = false, $penerima = false, $layanan = false)
    {
        $builder = $this->db->table('tb_layanan tl');
        $builder->select('tl.*, tp.nama_pelanggan, tp2.nama_penerima, cl.choice_name as layanan');
      
        // Subquery untuk menghitung jumlah order berdasarkan id_layanan
        $subQuery = "(SELECT COUNT(*) FROM tb_order to2 WHERE to2.id_layanan = tl.id_layanan) as in_order";
        $builder->select($subQuery, false); // false agar tidak di-escape oleh CI

        $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = tl.id_pelanggan', 'left');
        $builder->join('tb_penerima tp2', 'tp2.id_penerima = tl.id_penerima', 'left');
        $builder->join('choice_list cl', 'cl.id = tl.layanan', 'left');
        $builder->where('tl.deleted_at IS NULL');
        if ($id) {
            $builder->where('tl.id_layanan', $id);
        }
        if ($pengirim) {
            $builder->where('tl.id_pelanggan', $pengirim);
        }
        if ($penerima) {
            $builder->where('tl.id_penerima', $penerima);
        }
        if ($layanan) {
            $builder->where('tl.layanan', $layanan);
        }
        $builder->orderBy('tl.id_layanan', 'DESC');

        // echo $builder->getCompiledSelect();
        // exit; // Berhenti agar query tidak dieksekusi

        $build = $builder->get();

        if ($id) {
            return $build->getRowArray();
        }
        return $build->getResultArray();
    }

    /**
     * Method untuk mengambil data layanan berdasarkan ID.
     * @param int $id
     * @return array
     */
    public function getLayananById($id)
    {
        return $this->where(['id_layanan' => $id, 'deleted_at' => null])->first();
    }

    /**
     * Method untuk menghapus data layanan secara soft delete.
     * @param int $id
     * @return bool
     */
    public function softDeleteLayanan($id)
    {
        return $this->delete($id);
    }

    /**
     * Method untuk memulihkan data yang terhapus secara soft delete.
     * @param int $id
     * @return bool
     */
    public function restoreLayanan($id)
    {
        return $this->update($id, ['deleted_at' => null]);
    }
    public function getLayananNewOrder($pengirim, $penerima)
    {
        // return $this->where(['id_pelanggan' => $pengirim, 'id_penerima' => $penerima, 'deleted_at' => null])->get()->getResultArray();
        $builder = $this->db->table('tb_layanan tl');
        $builder->select('tl.*, cl.choice_name as layanan');
        $builder->join('choice_list cl', 'cl.id = tl.layanan', 'left');
        
        $builder->where('tl.deleted_at IS NULL');
        $builder->where('tl.id_pelanggan', $pengirim);
        $builder->where('tl.id_penerima', $penerima);
    
        $query = $builder->get();
    
        return $query->getResultArray();
    }
}

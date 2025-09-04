<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailOrderModel extends Model
{
  protected $table = 'tb_detail_order';
  protected $primaryKey = 'id_detail_order';

  // Daftar kolom yang digunakan dalam tabel
  protected $allowedFields = [
    'id_order',
    'id_barang',
    'jumlah',
    'berat',
    'panjang',
    'lebar',
    'tinggi',
    'volume'
  ];

  // Mengaktifkan fitur created_at dan updated_at jika diperlukan
  protected $useTimestamps = false; // Ubah ke true jika tabel memiliki kolom created_at dan updated_at

  /**
   * Fungsi untuk mendapatkan data detail order
   */
  public function getDetailOrders($id)
  {
    return $this->db->table('tb_detail_order as tdo')
      ->select('
                tdo.id_detail_order as id_detail, 
                tdo.id_barang as id_barang, 
                tb_barang.satuan as satuan,
                tb_barang.nama_barang as barang,
                tdo.jumlah as jumlah, 
                tdo.berat as berat, 
                tdo.panjang as panjang,
                tdo.lebar as lebar, 
                tdo.tinggi as tinggi, 
                tdo.volume as volume,
                (CASE WHEN dob.id_biaya IS NOT NULL THEN 1 ELSE 0 END) as has_packing
            ')
      ->join('tb_barang', 'tdo.id_barang = tb_barang.id_barang')
      ->join('detail_order_biaya as dob', 'tdo.id_detail_order = dob.id_detail_order AND dob.id_biaya = 9 AND dob.deleted_at IS NULL', 'left')
      ->where('tdo.id_order', $id)
      ->get()
      ->getResultArray();
  }
}

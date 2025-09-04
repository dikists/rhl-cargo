<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailOrderBiayaModel extends Model
{
  protected $table = 'detail_order_biaya';

  // Daftar kolom yang digunakan dalam tabel
  protected $allowedFields = [
    'id_detail_order',
    'id_biaya',
    'charge_value'
  ];
  // Soft delete
  protected $useSoftDeletes   = true;
  protected $deletedField     = 'deleted_at';

  // Timestamps
  protected $createdField     = 'created_at';
  protected $updatedField     = 'updated_at';
  // Mengaktifkan fitur created_at dan updated_at jika diperlukan
  protected $useTimestamps = true; // Ubah ke true jika tabel memiliki kolom created_at dan updated_at
}

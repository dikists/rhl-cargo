<?php

namespace App\Models;

use CodeIgniter\Model;

class ShipmentStatusModel extends Model
{
    protected $table = 'shipment_statuses'; // Nama tabel
    protected $primaryKey = 'status_id'; // Primary key tabel
    protected $useSoftDeletes = true; // Gunakan fitur soft delete

    protected $allowedFields = ['status_name']; // Kolom yang boleh diinputkan
    protected $useTimestamps = true; // Gunakan fitur timestamps (created_at, updated_at, deleted_at)
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    /**
     * Method untuk mendapatkan status berdasarkan kriteria tertentu.
     * 
     * @param array $conditions
     * @return array
     */
    public function getStatuses($conditions = [])
    {
        return $this->where($conditions)->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ShipmentStatusLogModel extends Model
{
    protected $table = 'shipment_status_log'; // Nama tabel
    protected $primaryKey = 'log_id'; // Primary key tabel
    protected $useSoftDeletes = false; // Gunakan fitur soft delete

    protected $allowedFields = ['shipment_id', 'status_id', 'changed_at']; // Kolom yang boleh diinputkan
    protected $useTimestamps = false;

    /**
     * Method untuk mendapatkan status berdasarkan kriteria tertentu.
     * 
     * @param array $conditions
     * @return array
     */
    public function getStatusLog($id_pengiriman)
    {
        $builder = $this->db->table('shipment_status_log');
        $builder->select('shipment_id, status_id, changed_at');
        $builder->join('shipment_statuses', 'shipment_statuses.status_id = shipment_status_log.status_id', 'left');
        $builder->where('shipment_status_log.shipment_id', $id_pengiriman);
        $query = $builder->get();
        return $query->getResultArray();
    }
}

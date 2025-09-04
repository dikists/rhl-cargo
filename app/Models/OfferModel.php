<?php

namespace App\Models;

use CodeIgniter\Model;

class OfferModel extends Model
{
    protected $table            = 'offers';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'offer_number',
        'pelanggan_id',
        'offer_date',
        'total_price',
        'status',
        'offer_text_title',
        'offer_text_opening',
        'offer_clause_desc',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    public function getOffer($id = null)
    {
        $result = $this->select('offers.*, tb_pelanggan.nama_pelanggan, tb_pelanggan.alamat_pelanggan, (SELECT SUM(total) FROM offer_details od WHERE od.offer_id = offers.id AND od.deleted_at IS NULL) AS total_offers')
            ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = offers.pelanggan_id', 'left')
            ->findAll();

        if ($id) {
            $result = $this->select('offers.*, tb_pelanggan.nama_pelanggan, tb_pelanggan.alamat_pelanggan, (SELECT SUM(total) FROM offer_details od WHERE od.offer_id = offers.id AND od.deleted_at IS NULL) AS total_offers')
                ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = offers.pelanggan_id', 'left')
                ->where('offers.id', $id)
                ->findAll();

            // echo $this->db->getLastQuery(); // Debug query terakhir
            // exit;
        }


        // echo $this->db->getLastQuery(); // Debug query terakhir
        // exit;

        return $result;
    }
}

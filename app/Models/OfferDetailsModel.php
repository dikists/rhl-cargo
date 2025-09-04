<?php
namespace App\Models;

use CodeIgniter\Model;

class OfferDetailsModel extends Model
{
    protected $table            = 'offer_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'offer_id',
        'item_name',
        'quantity',
        'price',
        'total'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getOfferDetails($offer_id)
    {
        return $this->where('offer_id', $offer_id)->findAll();
    }
}
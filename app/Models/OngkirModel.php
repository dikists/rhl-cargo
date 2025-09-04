<?php

namespace App\Models;

use CodeIgniter\Model;

class OngkirModel extends Model
{
    protected $table = 'ongkir_history';
    protected $allowedFields = [
        'origin_city_id', 'origin_city_name',
        'destination_city_id', 'destination_city_name',
        'courier', 'service', 'cost', 'etd', 'weight',
        'created_at'
    ];
    public $timestamps = false;
}

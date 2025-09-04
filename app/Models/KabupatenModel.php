<?php

namespace App\Models;

use CodeIgniter\Model;

class KabupatenModel extends Model
{
     protected $table = 'wilayah_kabupaten';
     protected $primaryKey = 'id';
     protected $allowedFields = ['provinsi_id','nama'];
     public function getKabupaten( $id = null)
     {
          if ($id !== null) {
               return $this->where('provinsi_id', $id)->findAll();
           }
       
           return $this->findAll();
     }
}

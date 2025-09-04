<?php

namespace App\Models;

use CodeIgniter\Model;

class KecamatanModel extends Model
{
     protected $table = 'wilayah_kecamatan';
     protected $primaryKey = 'id';
     protected $allowedFields = ['kabupaten_id','nama'];
     public function getKecamatan( $id = null)
     {
          if ($id !== null) {
               return $this->where('kabupaten_id', $id)->findAll();
           }
       
           return $this->findAll();
     }
}

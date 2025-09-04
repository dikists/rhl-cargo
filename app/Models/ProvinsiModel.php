<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinsiModel extends Model
{
     protected $table = 'wilayah_provinsi';
     protected $primaryKey = 'id';
     protected $allowedFields = ['nama'];
     public function getProvinsi(array $id = null)
     {
          if ($id) {
               return $this->where($id)->findAll();
          }

          return $this->findAll();
     }
}

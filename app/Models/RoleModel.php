<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
     protected $table = 'user_role';
     protected $primaryKey = 'role_id';
     protected $allowedFields = ['role_name','role_status'];
     public function getRole( $id = null)
     {
          if ($id !== null) {
               return $this
               ->where('role_id', $id)
               ->where('role_remove_at', null)
               ->findAll();
           }
       
           return $this->where('role_remove_at', null)->findAll();
     }
}

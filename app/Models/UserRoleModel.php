<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table            = 'user_role';
    protected $primaryKey       = 'role_id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'role_create_at',
        'role_create_by',
        'role_edit_at',
        'role_edit_by',
        'role_name',
        'role_status',
        'role_remove_at',
        'role_remove_by'
    ];

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

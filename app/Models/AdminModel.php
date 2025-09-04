<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
     protected $table = 'tb_user';
     protected $primaryKey = 'id';
     // Kolom-kolom yang diizinkan untuk diisi
     protected $allowedFields = [
          'kode_user',
          'full_name',
          'telepon',
          'email',
          'username',
          'password',
          'level',
          'foto',
          'role_id',
          'deleted_at',
          'created_at',
          'updated_at'
     ];

     // Timestamps otomatis untuk created_at dan updated_at
     protected $useTimestamps = true;
     protected $createdField  = 'created_at';
     protected $updatedField  = 'updated_at';
     protected $deletedField  = 'deleted_at';

     // Gunakan soft delete
     protected $useSoftDeletes = true;

     public function getDataAdmin($username)
     {
          return $this->select('tb_user.*, user_role.role_name')
               ->join('user_role', 'user_role.role_id = tb_user.role_id', 'left')
               ->where('tb_user.deleted_at', null)
               ->where('tb_user.username', $username)
               ->first();
     }
     public function getDataUsers($id = Null)
     {
          if(!$id){
               return $this->select('tb_user.*, user_role.role_name')
                    ->join('user_role', 'user_role.role_id = tb_user.role_id', 'left')
                    ->where('tb_user.deleted_at', null)
                    ->orderBy('id', 'DESC')
                    ->findAll();
          }
          return $this->select('tb_user.*, user_role.role_name')
               ->join('user_role', 'user_role.role_id = tb_user.role_id', 'left')
               ->where('tb_user.deleted_at', null)
               ->where('tb_user.id', $id)
               ->orderBy('id', 'DESC')
               ->get()->getRowArray();
     }
     public function getDriver($id = null){
          $builder = $this->db->table('tb_user tu');
          $builder->select('*');
          $builder->join('user_role ur', 'ur.role_id = tu.role_id', 'left');
          $builder->where('ur.role_name', 'driver');
          if($id != null){
               $builder->where('tu.id', $id);
               return $builder->get()->getRowArray();
          }
          return $builder->get()->getResultArray();
      }
}

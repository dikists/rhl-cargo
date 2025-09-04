<?php

namespace App\Models;

use CodeIgniter\Model;

class RelationUserModel extends Model
{
    protected $table            = 'relation_user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = ['user_id', 'relation_id', 'create_at', 'create_by'];

    // Optional: jika kamu menggunakan soft deletes dan timestamps
    protected $useTimestamps = false;
    protected $createdField  = 'create_at';

    // Untuk keamanan, bisa aktifkan ini kalau perlu
    // protected $returnType = 'array';
    // atau kalau pakai Entity:
    // protected $returnType = \App\Entities\RelationUser::class;
    public function delete_user_relation($id){
        $this->where('user_id', $id)->delete();
    }
    public function get_user_relation($id){
        $builder = $this->db->table($this->table);
        $builder->select('tp.*');
        $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = relation_user.relation_id', 'left');
        $builder->where('relation_user.user_id', $id);

        return $builder->get()->getResultArray();
    }
}

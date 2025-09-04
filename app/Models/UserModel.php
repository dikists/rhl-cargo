<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tb_pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $allowedFields = ['email', 'password'];

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}

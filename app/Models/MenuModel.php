<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menu';
    protected $primaryKey       = 'menu_id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'menu_parent',
        'menu_name',
        'menu_order',
        'menu_status',
        'menu_link',
        'menu_divider',
        'icon_class',
        'superadmin',
        'admin',
        'role_1',
        'role_2',
        'role_6',
        'role_7',
        'role_8'
    ];

    public function getMenu()
    {
        $builder = $this->db->table('menu');
        $builder->where('menu_status', 'Y');
        $builder->orderBy('menu_order', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }
}

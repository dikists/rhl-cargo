<?php

namespace App\Models;

use CodeIgniter\Model;

class ClauseModel extends Model
{
    protected $table = 'clause';
    protected $primaryKey = 'clause_id';
    protected $allowedFields = [
        'clause_name', 'clause_desc', 'clause_status', 'clause_create_at', 'clause_create_by', 
        'clause_edit_at', 'clause_edit_by', 'clause_remove_at', 'clause_remove_by'
    ];
}
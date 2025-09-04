<?php

namespace App\Models;

use CodeIgniter\Model;

class RelationTaxModel extends Model
{
     protected $table            = 'relation_tax';
     protected $primaryKey       = 'rtax_id';
     protected $allowedFields    = [
          'rtax_relation_id',
          'rtax_category_id',
          'rtax_ppn_type',
          'rtax_value',
          'rtax_gunggung',
          'rtax_date_start',
          'rtax_date_end',
          'rtax_desc',
          'rtax_create_at',
          'rtax_create_by',
          'rtax_edit_at',
          'rtax_edit_by',
          'rtax_status'
     ];

     public function getAll()
     {
          return $this->findAll();
     }

     public function getTax($id)
     {
          return $this->db->table($this->table)
               ->select('*')
               ->join('choice_list', 'choice_list.id = relation_tax.rtax_category_id', 'left')
               ->where('rtax_status', 'Y')
               ->where('rtax_relation_id', $id)
               ->orderBy('rtax_id')
               ->get()
               ->getResultArray();
     }

     public function db_get_enum_array($columnName)
     {
          $db = \Config\Database::connect();
          $query = $db->query("SHOW COLUMNS FROM {$this->table} LIKE ?", [$columnName]);
          $row = $query->getRowArray();

          $rowType = $row["Type"];

          $enum = explode(",", str_replace(array("enum(", ")", "'"), "", $rowType));
          return $enum;
     }
     public function getById($id)
     {
          return $this->where('rtax_id', $id)->first();
     }

     public function insertData($data)
     {
          return $this->insert($data);
     }

     public function updateData($id, $data)
     {
          return $this->update($id, $data);
     }

     public function deleteData($id)
     {
          return $this->delete($id);
     }
}

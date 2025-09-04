<?php

namespace App\Models;

use CodeIgniter\Model;

class ManifestModel extends Model
{
     protected $table      = 'tb_manifest';
     protected $primaryKey = 'manifest_id';

     protected $useAutoIncrement = true;
     protected $useTimestamps    = true;
     protected $returnType       = 'array';
     protected $useSoftDeletes   = true;
     protected $deletedField = 'remove_at';
     protected $allowedFields = ['manifest_number', 'date','vendor_id', 'created_at', 'updated_at', 'remove_at'];

     // Untuk query secara langsung
     public function getManifest($id=false)
     {
          $cond = '';
          if($id){
               $cond = " AND tm.manifest_id = $id";
          }
          $sql = "
               SELECT 
                    tm.*, 
                    tv.vendor_name, 
                    COALESCE(
                         JSON_ARRAYAGG(
                              JSON_OBJECT(
                                   'no_surat_jalan', tsj.no_surat_jalan,
                                   'id_surat_jalan', tsj.id_surat_jalan,
                                   'id_order', tsj.id_order,
                                   'sub_vendor_name', sub_vendor.vendor_name,
                                   'sub_vendor_short_name', sub_vendor.short_name
                              )
                         ), 
                         JSON_ARRAY()
                    ) AS detail_list,
                    (select count(*) from tb_manifest_detail tmd2 where tmd2.manifest_id = tm.manifest_id AND tmd2.deleted_at IS NULL AND tmd2.status = 1 and tmd2.surat_jalan_id != 0) as total_detail
               FROM 
                    tb_manifest tm
               LEFT JOIN 
                    tb_vendor tv ON tv.vendor_id = tm.vendor_id AND tv.remove_at IS NULL
               LEFT JOIN 
                    tb_manifest_detail tmd ON tmd.manifest_id = tm.manifest_id and tmd.deleted_at is null and tmd.status = 1
               LEFT JOIN 
             		tb_surat_jalan tsj on tsj.id_surat_jalan = tmd.surat_jalan_id 
               LEFT JOIN 
             		tb_vendor sub_vendor on sub_vendor.vendor_id = tmd.sub_vendor_id 
               WHERE 
                    tm.remove_at IS NULL
                    and tm.date != '0000-00-00'
                    $cond
               GROUP BY 
               tm.manifest_id
               ORDER BY 
               tm.created_at DESC
        ";
     //    echo "<pre>";
     //    print_r($sql);
     //    echo "</pre>";
     //    die;

          return $this->db->query($sql)->getResultArray();
     }

     // Menambahkan fungsi untuk mengambil data berdasarkan ID
     public function getManifestById($id)
     {
          return $this->where('manifest_id', $id)->first();
     }

     // Jika Anda ingin menambahkan filter untuk status 'removed'
     public function getActiveManifests()
     {
          return $this->where('remove_at', null)->findAll();
     }
}

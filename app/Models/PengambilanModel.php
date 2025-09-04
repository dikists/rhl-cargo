<?php

namespace App\Models;

use CodeIgniter\Model;

class PengambilanModel extends Model
{
     protected $table            = 'tb_pengambilan'; // Nama tabel
     protected $primaryKey       = 'id_pengambilan'; // Primary key

     // Kolom yang dapat diisi (fillable)
     protected $allowedFields    = [
          'id_surat_jalan',
          'id_order',
          'kode_kurir',
          'tanggal_ambil',
          'waktu_ambil',
          'created_at',
          'updated_at',
          'deleted_at'
     ];

     // Menggunakan fitur soft delete
     protected $useSoftDeletes   = true;
     protected $deletedField     = 'deleted_at';

     // Otomatis mengelola waktu
     protected $useTimestamps    = true;
     protected $createdField     = 'created_at';
     protected $updatedField     = 'updated_at';

     /**
      * Ambil semua data pengambilan atau berdasarkan kondisi.
      *
      * @param mixed $id
      * @return array|null
      */
     public function getPengambilan2($id = null)
     {
          $builder = $this->db->table($this->table);
          $builder->select('*');
          $builder->join('tb_order AS or2', 'or2.id_order = tb_surat_jalan.id_order', 'left');
          $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = or2.id_pelanggan', 'left');
          $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = or2.id_penerima', 'left');
          $builder->join('tb_layanan AS tl', 'tl.id_layanan = or2.id_layanan', 'left');
          $builder->where('or2.deleted_at IS NULL');
          $builder->where('tb_surat_jalan.deleted_at IS NULL');
          if ($id !== false) {
               $builder->where('tb_surat_jalan.id_surat_jalan', $id);
          }
          $builder->orderBy('id_surat_jalan', 'DESC');
          echo $builder->getCompiledSelect();
          exit;
          if ($id !== false) {
               return $builder->get()->getRowArray();
          } else {
               return $builder->get()->getResultArray();
          }
     }
     public function getPengambilan($id = null)
     {
          $cekManifest = $this->db->table('tb_detail_order')
               ->selectSum('jumlah')
               ->where('id_order', 'to2.id_order', false);

          $builder = $this->db->table($this->table . ' tp')
               ->select('tp.id_pengambilan,tp.tanggal_ambil,tsj.no_surat_jalan, to2.no_order, tp2.nama_pelanggan, cl.choice_name as layanan, tp3.nama_penerima, tp.created_at, tu.username as driver_name, (SELECT COUNT(*) FROM tb_manifest_detail tmd WHERE tmd.surat_jalan_id = tp.id_surat_jalan AND tmd.deleted_at IS NULL) as in_manifest')
               ->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tp.id_surat_jalan', 'inner')
               ->join('tb_order to2', 'to2.id_order = tp.id_order', 'inner')
               ->join('tb_pelanggan tp2', 'tp2.id_pelanggan = to2.id_pelanggan', 'inner')
               ->join('tb_penerima tp3', 'tp3.id_penerima = to2.id_penerima', 'inner')
               ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'inner')
               ->join('choice_list cl', 'cl.id = tl.layanan', 'left')
               ->join('tb_user tu', 'tu.id = tp.kode_kurir', 'inner')
               ->where('tsj.deleted_at', null)
               ->where('to2.deleted_at', null)
               ->where('tp.deleted_at', null)
               ->orderBy('tp.id_pengambilan', 'DESC');

          // echo $this->db->showLastQuery();
          // echo $builder->getCompiledSelect();
          // exit;

          if ($id !== null) {
               $builder->where('tp.id', $id); // Sesuaikan kolom ID
               return $builder->get()->getRowArray();
          } else {
               return $builder->get()->getResultArray();
          }
     }
     public function update_shipment($id, $data)
     {
          $builder = $this->db->table($this->table);
          $builder->where('id_pengambilan', $id);
          $builder->update($data);
     }
}

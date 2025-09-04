<?php

namespace App\Models;

use CodeIgniter\Model;

class ManifestDetailModel extends Model
{
    protected $table      = 'tb_manifest_detail'; // Nama tabel
    protected $primaryKey = 'id_detail_manifest';  // Primary key tabel

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['manifest_id', 'surat_jalan_id', 'sub_vendor_id', 'no_surat_jalan', 'status', 'created_at', 'updated_at', 'deleted_at'];

    protected $useTimestamps = true;


    // Jika ingin menambahkan query builder khusus
    public function getDetailManifest($id)
    {
        $builder = $this->db->table($this->table . ' tmd')
            ->select('tmd.*, tsj.no_surat_jalan, tp.nama_pelanggan, tp2.nama_penerima, tv.vendor_name, kab.nama as kota')
            ->join('tb_surat_jalan tsj', 'id_surat_jalan = tmd.surat_jalan_id', 'left')
            ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
            ->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left')
            ->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left')
            ->join('tb_vendor tv', 'tv.vendor_id = tmd.sub_vendor_id', 'left')
            ->join('wilayah_kabupaten kab', 'kab.id = tp2.kabupaten_id', 'left')
            ->where('tmd.manifest_id', $id)
            ->where('tmd.deleted_at is null')
            ->where('tmd.status', 1);

        // DEBUG: tampilkan query mentah
        // echo $builder->getCompiledSelect();
        // exit;

        return $builder->get()->getResultArray();
    }

    public function getPDFDetailManifest($id)
    {
        return $this->db->table($this->table . ' tmd')
            ->select('tsj.no_surat_jalan, tp.nama_pelanggan, tp2.nama_penerima,kab.nama as kota, tv.vendor_name, 
                  ROUND(SUM((tdo.panjang * tdo.lebar * tdo.tinggi / tl.divider) * tdo.jumlah)) AS total_volume,
                  SUM(tdo.berat * tdo.jumlah) AS total_berat,
                  SUM(tdo.jumlah) AS total_jumlah, tl.divider as divider, cl.choice_name as layanan'
                  )
            ->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tmd.surat_jalan_id', 'left')
            ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
            ->join('tb_detail_order tdo', 'tdo.id_order = to2.id_order', 'left')
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->join('choice_list cl', 'cl.id = tl.layanan', 'left')
            ->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left')
            ->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left')
            ->join('tb_vendor tv', 'tv.vendor_id = tmd.sub_vendor_id', 'left')
            ->join('wilayah_kabupaten kab', 'kab.id = tp2.kabupaten_id', 'left')
            ->where('tmd.manifest_id', $id)
            ->where('tmd.deleted_at is null')
            ->where('tmd.status', 1)
            ->groupBy('tmd.manifest_id, tsj.id_surat_jalan, to2.id_order')
            ->get()
            ->getResultArray();
    }

    public function getSJManifest()
    {
        return $this->db->table($this->table . ' tmd')
            ->select('tmd.*, tsj.no_surat_jalan, tp.nama_pelanggan, tp2.nama_penerima')
            ->join('tb_surat_jalan tsj', 'id_surat_jalan = tmd.surat_jalan_id', 'left')
            ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
            ->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left')
            ->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left')
            ->where('tsj.no_surat_jalan is null')
            ->where('tmd.deleted_at is not null')
            ->get()
            ->getResultArray();
    }
}
